<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once( __DIR__ . '/blog.php' );
$blog = new BlogClass;

//var_dump($url->rest_path);
$current_category = $post_id = 0;
$page = 1;
$date_show = false;

if ($url->rest_path){
    $s = 0;
    // todo: check url to show category

    // filter by date type
    if (substr($url->rest_path[$s], 0, 5) == 'date-'){
        $date_show = substr($url->rest_path[$s], 5);
        $s++;
    }
    // check if offset needed by page /////////////////////////////////////////////////////
    if ($url->rest_path[$s] and substr($url->rest_path[$s], 0, 4) == 'page') {
        $page = substr($url->rest_path[$s], 4);
        $page = intval($page);
        $s++;
    }
    // One post /////////////////////////////////////////////////////////
    if (!empty($url->rest_path[$s])){
        $post_item = $blog->getPostItem($url->rest_path[$s]);
        $smarty->assign("post_item", $post_item);

        // meta SEO functions start
        $meta_seo_item = $post_item['id'];
        $meta_seo_item_type = 'blog';
        include_once( __DIR__ . '/inc_meta_seo.php' );
        // meta SEO functions finish
    }
}

if (empty($url->rest_path) || $page < 2){
    $post_main_one = $blog->getPostMainOne();
    $smarty->assign("post_main_one", $post_main_one);
}
//
// Post list + pagination & filters by taxonomies
if ($post_id==0) {
    $smarty->assign("post_list", $blog->getPostList($page, $conf->conf_settings['count_post_page'], $current_category, $conf->conf_settings['count_post_page_index'], $date_show));
    $smarty->assign("post_pages", $blog->getPostPages($page, $conf->conf_settings['count_post_page'], $current_category, $conf->conf_settings['count_post_page_index'], $date_show));
}
//$smarty->assign("current_category", $blog->getPostCategoryItem($current_category)); // Категория в шаблон
$smarty->assign("current_page", $page);
//$smarty->assign("date_show", $date_show);
//$smarty->assign("date_show_date", ($date_show)?$date_show:date("Y-m-d"));
//
////$menu_array = $blog->getNewsMenu($current_category); // Меню новостей
////$smarty->assign("menu_array", $menu_array); // Меню в шаблон

if (empty($post_item)) {
    // meta SEO functions start
    $meta_seo_item = $url->page['p_id'];
    $meta_seo_item_type = 'page';
    include_once('classes/inc_meta_seo.php');
    // meta SEO functions finish
}

// Banners
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