<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('classes/page.php');
$page = new page;

$page_child_list = $page->getPageChildList($url->page['p_id']);
$smarty->assign("page_child_list", $page_child_list);

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