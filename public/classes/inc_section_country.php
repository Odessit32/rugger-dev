<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

include_once('classes/section_country.php');
$sectionCountry = new SectionCountry;

include_once('classes/page.php');
$page = new page;

if ($url->page['p_adress'] == 'history'){
    include_once('classes/informers.php');
    $informers = new informers;
    $photo_informer = $informers->getPhotoInformer(15);
    $smarty->assign("photo_informer", $photo_informer);
}

$ph_page_item = $page->getPhPageItem($url->page['p_id']);
$smarty->assign("ph_page_item", $ph_page_item);

// News /////////////////////////////////////////////////
include_once('classes/news.php');
$news = new news;
$news_main_one = $news->getNewsMainOne();
$smarty->assign("news_main_one", $news_main_one);

// Championships
$champ_group_list = $sectionCountry->getCountryChanpionships();
$smarty->assign("champ_group_list", $champ_group_list);

// meta SEO functions start
$meta_seo_item = $url->page['p_id'];
$meta_seo_item_type = 'page';
include_once('classes/inc_meta_seo.php');
// meta SEO functions finish

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