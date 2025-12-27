<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('classes/news.php');
$news = new news;

//var_dump($url->rest_path);
$current_category = $news_id = 0;
$page = 1;
$date_show = false;
$news_categories = $news->getNewsCategories();

// Проверяем, является ли текущий пользователь администратором
$isAdmin = !empty($admin_user) && (
    (!empty($admin_user['admin_status']) && $admin_user['admin_status'] == 'yes') ||
    (!empty($admin_user['publisher_status']) && $admin_user['publisher_status'] == 'yes')
);

if ($url->rest_path){
    $s = 0;
    // ��������� �� ���������� �� ���� ������ ////////////////////////////
    if ($news_categories) {
        foreach ($news_categories as &$c_item){
            if (!empty($url->rest_path[$s]) && $url->rest_path[$s] == $c_item['nc_address']) {
                $current_category = $c_item['nc_id'];
                $c_item['active'] = 'yes';
                $s++;
            } else {
                $c_item['active'] = 'no';
            }
        }
    }
    // filter by date type
    if (isset($url->rest_path[$s]) && substr($url->rest_path[$s], 0, 5) == 'date-'){
        $date_show = substr($url->rest_path[$s], 5);
        $s++;
    }
    // ��������� �������� /////////////////////////////////////////////////////
    if (isset($url->rest_path[$s]) and substr($url->rest_path[$s], 0, 4) == 'page') {
        $page = substr($url->rest_path[$s], 4);
        $page = intval($page);
        $s++;
    }
    // ��������� ������� /////////////////////////////////////////////////////////
    if (isset($url->rest_path[$s]) and is_numeric($url->rest_path[$s])){
        $news_id = intval($url->rest_path[$s]);
        $news_item = $news->getNewsItem($news_id, $isAdmin);
        $smarty->assign("news_item", $news_item);
        if (!empty($news_item['n_nc_id'])){
            foreach ($news_categories as &$c_item){
                if ($news_item['n_nc_id'] == $c_item['nc_id']) {
                    $c_item['active'] = 'yes';
                    $s++;
                } else {
                    $c_item['active'] = 'no';
                }
            }
            $current_category = $news_item['n_nc_id'];
        } else {
            $news_categories[0]['active'] = 'yes';
        }

        // meta SEO functions start
        $meta_seo_item = $news_item['id'];
        $meta_seo_item_type = 'news';
        include_once('classes/inc_meta_seo.php');
        // meta SEO functions finish
    }
}
if (empty($current_category)){
    $news_categories[0]['active'] = 'yes';
}

$smarty->assign("news_categories", $news_categories);
if (!$url->rest_path or $page < 2){
    $news_main_one = $news->getNewsMainOne();
    $smarty->assign("news_main_one", $news_main_one);
}

// ������ �������� ��� ��������� ��� ��� ��������� + ������� ��� �������
if ($news_id==0) {
    $smarty->assign("news_list", $news->getNewsList($page, $conf->conf_settings['count_news_page'], $current_category, $conf->conf_settings['count_news_page_index'], $date_show, $isAdmin));
    $smarty->assign("news_pages", $news->getNewsPages($page, $conf->conf_settings['count_news_page'], $current_category, $conf->conf_settings['count_news_page_index'], $date_show, $isAdmin));
}
$smarty->assign("current_category", $news->getNewsCategoryItem($current_category)); // ��������� � ������
$smarty->assign("current_page", $page);
$smarty->assign("date_show", $date_show);
$smarty->assign("date_show_date", ($date_show)?$date_show:date("Y-m-d"));

//$menu_array = $news->getNewsMenu($current_category); // ���� ���������
//$smarty->assign("menu_array", $menu_array); // ���� � ������

if (empty($news_item)) {
    // meta SEO functions start
    $meta_seo_item = $url->page['p_id'];
    $meta_seo_item_type = 'page';
    include_once('classes/inc_meta_seo.php');
    // meta SEO functions finish
}

// �������
include_once('classes/banners.php');
$banners = new banners;
$pbi = $banners->getPageBanInfList($url->page['p_id'], $url->page['p_c_banners']);
$smarty->assign("pbi", $pbi);

if ($pbi['classes']) {
    foreach ($pbi['classes'] as $item) {
        if (!empty($item) && file_exists('classes/'.$item)){
            include_once('classes/'.$item);
        }
    }
}
