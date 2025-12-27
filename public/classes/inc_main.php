<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

include_once('classes/main.php');
$main = new main;

// mbi
$mbi = $main->getMainBanInfList($conf->conf_settings['mbi_left'], $conf->conf_settings['mbi_center'], $conf->conf_settings['mbi_right']);
$smarty->assign("mbi", $mbi);
if ($mbi['classes']) {
    foreach ($mbi['classes'] as $item) {
        if (!empty($item) && file_exists('classes/'.$item)){
            include_once('classes/'.$item);
        }
    }
}

// meta SEO functions start
$meta_seo_item = 1;
$meta_seo_item_type = 'main';
include_once('classes/inc_meta_seo.php');
// meta SEO functions finish

//  /////////////////////////////////////////////////
include_once('classes/news.php');
$news = new news;
$news_main_one = $news->getNewsMainOne();
$smarty->assign("news_main_one", $news_main_one);
if (IS_CACHING_U && !empty($caching)) {
    $caching->setCachedTemplate($cached_key, "index.tpl");
    $smarty->display("index.tpl", $cached_key);
} else {
    $smarty->display("index.tpl");
}
