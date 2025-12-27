<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

include_once('classes/search.php');
$search = new search;
$s = 0;
$page = 1;
// Текущая страница /////////////////////////////////////////////////////
if (isset($url->rest_path[$s]) && str_starts_with($url->rest_path[$s], 'page')) {
    $page = (int) substr($url->rest_path[$s], 4);
    $s++;
}
if (is_array($url->rest_path) && isset($url->rest_path[$s])) {
    $q_search = $url->rest_path[$s];
    $s++;
}

if ($_POST['q_search'] != '') $q_search = $_POST['q_search'];
else $q_search = rawurldecode($q_search);
$q_search = strtolower($q_search);
$search_q = array("'", '"', "\\");
$replace = array('', '', '', '');
str_replace($search_q, $replace, $q_search);
if ($q_search) {
    $smarty->assign("q_search", $q_search);
    $smarty->assign("q_search_url", rawurlencode($q_search));
}
$res_search = $search->getSearchList($page, $conf->conf_settings['count_search_page'], $q_search);
$smarty->assign("search_list", $res_search['res']);
$smarty->assign("search_pages", $search->getSearchPages($page, $conf->conf_settings['count_search_page'], $res_search['count']));
$smarty->assign("current_page", $page);

if ($page == 1 and $q_search != '') $search->saveSearch($q_search);

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