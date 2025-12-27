<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('classes/news.php');
$news = new news;

$smarty->assign("news_main_list", $news->getNewsMainList($conf->conf_settings['count_news_left']));