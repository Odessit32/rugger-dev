<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

include_once('classes/staff.php');
$staff = new staff;


$staff_item = false;
$staff_page = 'profile';

if ($url->rest_path) {
    $s = 0;
    if (!empty($url->rest_path[$s])) {
        $staff_item = $staff->getStaffItem($url->rest_path[$s]);
        if (empty($staff_item['text']) && empty($staff_item['description']) && $staff_page == 'profile') {
            $staff_page = 'statistics';
        }
        // meta SEO functions start
        $meta_seo_item = $staff_item['id'];
        $meta_seo_item_type = 'staff';
        include_once('classes/inc_meta_seo.php');
        // meta SEO functions finish
    }
    $s++;
    if (!empty($url->rest_path[$s])) {
        switch($url->rest_path[$s]) {
            case 'staff':
//                $staff_item['staff'] = $staff->getTeamStaff($staff_item['id']);
                $staff_page = 'staff';
                break;
            case 'statistics':
                $staff_page = 'statistics';
                break;
            case 'news':
////                $staff_item['news'] = $staff->getTeamNews($staff_item['id']);
                $staff_page = 'news';
                break;
            case 'photos':
                $staff_item['photos'] = $staff->getStaffPhoto($staff_item['id']);
                $staff_page = 'photos';
                break;
            case 'video':
                $staff_item['videos'] = $staff->getStaffVideo($staff_item['id']);
                $staff_page = 'videos';
                break;
        }
        $s++;
    }
}
$smarty->assign("staff_page", $staff_page);
$smarty->assign("staff_item", $staff_item);



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