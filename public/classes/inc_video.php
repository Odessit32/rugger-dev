<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

include_once('classes/video.php');
$video = new video;

$current_category = $gallegy_id = $video_id = 0;
$video_item = false;
$page = 1;

$categories_all = $video->getVideoCategories();
$categories_list = $categories_all['list'];
$categories = $categories_all['by_id'];
$smarty->assign("categories", $categories);

if ($url->rest_path){
    $s = 0;
    // �������� ���� ����� ���������� ���������  ////////////////////////////
    if (!empty($categories_list)) {
        foreach ($categories_list as &$c_item){
            if (isset($url->rest_path[$s]) && $url->rest_path[$s] == $c_item['address']) {
                $current_category = $c_item['id'];
                $c_item['active'] = 'yes';
                $s++;
            } else {
                $c_item['active'] = 'no';
            }
        }
    }
    // ������� �������� /////////////////////////////////////////////////////
    if (isset($url->rest_path[$s]) && substr($url->rest_path[$s], 0, 4) == 'page') {
        $page = substr($url->rest_path[$s], 4);
        $page = intval($page);
        $s++;
    }
    // ���� ������� /////////////////////////////////////////////////////////
    if (isset($url->rest_path[$s]) && substr($url->rest_path[$s], 0, 3) == 'gal') {
        $gallegy_id = substr($url->rest_path[$s], 3);
        $gallegy_item = $video->getGalleryItem($gallegy_id);
        $smarty->assign("gallegy_item", $gallegy_item);
        $current_category = $gallegy_item['vg_vc_id'];
        $s++;
        // ���� ����� /////////////////////////////////////////////////////////
        if (isset($url->rest_path[$s]) && is_numeric($url->rest_path[$s])) {
    $video_id = (int) $url->rest_path[$s];

    if (isset($gallegy_item['videos']) && is_array($gallegy_item['videos'])) {
        foreach ($gallegy_item['videos'] as $item) {
            if ($item['v_id'] == $video_id) {
                $video_item = $item;
                $video_item['active'] = true;
                break; // ��� ������ ����� � ����� ����� �� �����
            }
        }
    }

    $s++;

        } else {
            $video_item = $gallegy_item['videos'][0];
            $video_id = $gallegy_item['videos'][0]['v_id'];
            $video_item['active'] = false;
        }
        if (!empty($gallegy_item['vg_vc_id'])){
            foreach ($categories_list as &$c_item){
                if ($gallegy_item['vg_vc_id'] == $c_item['id']) {
                    $c_item['active'] = 'yes';
                    $s++;
                } else {
                    $c_item['active'] = 'no';
                }
            }
            $current_category = $gallegy_item['vg_vc_id'];
        } else {
            $categories_list[0]['active'] = 'yes';
        }

        $smarty->assign("video_id", $video_id);
        $smarty->assign("video_item", $video_item);
    }
}
if (empty($current_category)){
    $categories_list[0]['active'] = 'yes';
}

//	if ($page < 2 && empty($gallegy_item)) {
//		$gallegy_item = $video->getGalleryItem();
//		$gallegy_id = $gallegy_item['id'];
//		$video_item = $gallegy_item['videos'][0];
//		$video_id = $gallegy_item['videos'][0]['v_id'];
//		$video_item['active'] = false;
//		$smarty->assign("gallegy_item", $gallegy_item);
//		$smarty->assign("video_id", $video_id);
//		$smarty->assign("video_item", $video_item);
//	}
// ������ ������� ��� ��������� ��� ��� ��������� + �������� ��� ����� ������
$smarty->assign("gallery_list", $video->getGalleryList($page, $conf->conf_settings['count_video_page'], $current_category, $gallegy_id, $conf->conf_settings['count_video_page_index']));
$smarty->assign("gallery_pages", $video->getGalleryPages($page, $conf->conf_settings['count_video_page'], $current_category, $gallegy_id, $conf->conf_settings['count_video_page_index']));

$smarty->assign("current_category", (!empty($categories[$current_category]))?$categories[$current_category]:0); // ��������� � ������
$smarty->assign("current_page", $page);

$video->getVideoMenu($categories_all['list'], $current_category); // ����
$menu_array = $categories_all['list'];
$smarty->assign("menu_array", $menu_array); // ���� � ������
$smarty->assign("categories_list", $categories_list);

// meta SEO functions start
$meta_seo_item = $url->page['p_id'];
$meta_seo_item_type = 'page';
include_once('classes/inc_meta_seo.php');
// meta SEO functions finish

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