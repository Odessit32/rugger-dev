<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

include_once('classes/page.php');
$page = new page;

$smarty->assign("sitemap_list", $page->getSiteMap(0, 0, 0, 'yes', '', 'no', 'p_order', 'DESC'));

// Баннеры
include_once('classes/banners.php');
$banners = new banners;
$pbi = $banners->getPageBanInfList($url->page['p_id'], $url->page['p_c_banners']);
$smarty->assign("pbi", $pbi);

if ($pbi['classes']) foreach ($pbi['classes'] as $item) include_once('classes/' . $item);
?>