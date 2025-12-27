<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

include_once('classes/photo.php');
$photo = new photo;

$current_category = $gallegy_id = 0;
$page = 1;
$gallegy_item = array();

$categories_all = $photo->getPhotoCategories();
$categories_list = $categories_all['list'];
$categories = $categories_all['by_id'];
$smarty->assign("categories", $categories);

if ($url->rest_path){
    $s = 0;
    // просмотр если нужно отобразить категорию  ////////////////////////////
    if (!empty($categories_list)) {
        foreach ($categories_list as &$c_item){
            if ($url->rest_path[$s] == $c_item['address']) {
                $current_category = $c_item['id'];
                $c_item['active'] = 'yes';
                $s++;
            } else {
                $c_item['active'] = 'no';
            }
        }
    }
    // Текущая страница /////////////////////////////////////////////////////
    if ($url->rest_path[$s] && substr($url->rest_path[$s], 0, 4) == 'page') {
        $page = substr($url->rest_path[$s], 4);
        $page = intval($page);
        $s++;
    }
    // Одна галерея /////////////////////////////////////////////////////////
    if (!empty($url->rest_path[$s]) && substr($url->rest_path[$s], 0, 3) == 'gal') {
        $gallegy_id = substr($url->rest_path[$s], 3);
        $gallegy_item = $photo->getPhotoGalleryItem($gallegy_id);
        $smarty->assign("gallegy_item", $gallegy_item);
        $current_category = $gallegy_item['phg_phc_id'];
        if (!empty($gallegy_item['phg_phc_id'])){
            foreach ($categories_list as &$c_item){
                if ($gallegy_item['phg_phc_id'] == $c_item['id']) {
                    $c_item['active'] = 'yes';
                    $s++;
                } else {
                    $c_item['active'] = 'no';
                }
            }
        } else {
            $categories_list[0]['active'] = 'yes';
        }
    }
}

if (empty($current_category)){
    $categories_list[0]['active'] = 'yes';
}

// Списки галерей для категирий или без категорий + страницы для этого списка
//	if ($page < 2 && empty($gallegy_item)) {
//		$gallegy_item = $photo->getPhotoGalleryItem();
//		$gallegy_id = $gallegy_item['id'];
//		$smarty->assign("gallegy_item", $gallegy_item);
//	}
$smarty->assign("categories_list", $categories_list);

$smarty->assign("gallery_list", $photo->getGalleryList($page, $conf->conf_settings['count_photo_page'], $current_category, $gallegy_id, $conf->conf_settings['count_photo_page_index']));
$smarty->assign("gallery_pages", $photo->getGalleryPages($page, $conf->conf_settings['count_photo_page'], $current_category, $gallegy_id, $conf->conf_settings['count_photo_page_index']));

$smarty->assign("current_category", $categories[$current_category]); // Категория в шаблон
$smarty->assign("current_page", $page);
/*
$photo->getPhotoMenu($current_category, $categories_all['list']); // Меню
$menu_array = $categories_all['list'];
$smarty->assign("menu_array", $menu_array); // Меню в шаблон

*/

//$smarty->assign("category_list", $categories_all['list']);

// meta SEO functions start
$meta_seo_item = $url->page['p_id'];
$meta_seo_item_type = 'page';
include_once('classes/inc_meta_seo.php');
// meta SEO functions finish

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