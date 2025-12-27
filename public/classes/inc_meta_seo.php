<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('classes/meta_seo.php');
$metaSeo = new metaSeo;

global $meta_seo_item;
global $meta_seo_item_type;
$meta_seo_item = $metaSeo->getMetaItem($meta_seo_item, $meta_seo_item_type);
$smarty->assign("meta_seo_item", $meta_seo_item);
