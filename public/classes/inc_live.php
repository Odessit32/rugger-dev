<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('classes/live.php');
$live = new live;

//var_dump($url->rest_path);
$current_category = $live_id = 0;
$page = 1;
$date_show = false;
$live_categories = $live->getLiveCategories();

if ($url->rest_path){
    $s = 0;
    // просмотр не категорию ли нужно отобразить ////////////////////////////
    if ($live_categories) {
        foreach ($live_categories as &$c_item){
            if ($url->rest_path[$s] == $c_item['nc_address']) {
                $current_category = $c_item['nc_id'];
                $c_item['active'] = 'yes';
                $s++;
            } else {
                $c_item['active'] = 'no';
            }
        }
    }
    // filter by date type
    if (substr($url->rest_path[$s], 0, 5) == 'date-'){
        $date_show = substr($url->rest_path[$s], 5);
        $s++;
    }
    // Текущая страница /////////////////////////////////////////////////////
    if ($url->rest_path[$s] and substr($url->rest_path[$s], 0, 4) == 'page') {
        $page = substr($url->rest_path[$s], 4);
        $page = intval($page);
        $s++;
    }
    // Одна новость /////////////////////////////////////////////////////////
    if (!empty($url->rest_path[$s])){
        $live_address = addslashes($url->rest_path[$s]);
        $live_item = $live->getLiveItem($live_address);
        $smarty->assign("live_item", $live_item);
        if (!empty($live_item['n_nc_id'])){
            foreach ($live_categories as &$c_item){
                if ($live_item['n_nc_id'] == $c_item['nc_id']) {
                    $c_item['active'] = 'yes';
                    $s++;
                } else {
                    $c_item['active'] = 'no';
                }
            }
            $current_category = $live_item['n_nc_id'];
        } else {
            $live_categories[0]['active'] = 'yes';
        }

        // meta SEO functions start
        $meta_seo_item = $live_item['id'];
        $meta_seo_item_type = 'live';
        include_once('classes/inc_meta_seo.php');
        // meta SEO functions finish
    }
}
if (empty($current_category)){
    $live_categories[0]['active'] = 'yes';
}

$smarty->assign("live_categories", $live_categories);

// Списки новостей для категирий или без категорий + страницы для этого списка
if ($live_id==0) {
    $smarty->assign("live_list", $live->getLiveList($page, $conf->conf_settings['count_live_page'], $current_category, $conf->conf_settings['count_live_page_index'], $date_show));
    $smarty->assign("live_pages", $live->getLivePages($page, $conf->conf_settings['count_live_page'], $current_category, $conf->conf_settings['count_live_page_index'], $date_show));
}
$smarty->assign("current_category", $live->getLiveCategoryItem($current_category)); // Категория в шаблон
$smarty->assign("current_page", $page);
$smarty->assign("date_show", $date_show);
$smarty->assign("date_show_date", ($date_show)?$date_show:date("Y-m-d"));

//$menu_array = $live->getLiveMenu($current_category); // Меню новостей
//$smarty->assign("menu_array", $menu_array); // Меню в шаблон

if (empty($live_item)) {
    // meta SEO functions start
    $meta_seo_item = $url->page['p_id'];
    $meta_seo_item_type = 'page';
    include_once('classes/inc_meta_seo.php');
    // meta SEO functions finish
}

// Баннеры
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