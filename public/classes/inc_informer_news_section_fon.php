<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
if ($url->page['p_adress'] == 'news' && !empty($news_item) && !empty($url->section)){
	include_once('classes/news.php');
	$news = new news;
	
	$smarty->assign("s_fon_news_main_list", $news->getNewsMainList($conf->conf_settings['count_news_left'], 0, false));
}