<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('classes/news.php');
$news = new news;

// Получаем ID рубрик, отмеченных для вывода в виджете
$widget_categories = $news->getWidgetCategoryIds();

// Если есть рубрики для виджета - используем их, иначе fallback на категорию 2 (Статьи)
$a_category = !empty($widget_categories) ? $widget_categories : 2;
$smarty->assign("article_news_main_list", $news->getNewsMainList(3, 0, false, $a_category)); // perpage, offset, is not all, article categories