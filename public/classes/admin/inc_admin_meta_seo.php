<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('../classes/admin/admin_meta_seo.php');
$metaSeo = new metaSeo;

if(!empty($_POST['save_meta_seo'])){
    if ($metaSeo->saveMetaItem()) $smarty->assign("ok_message", 'Meta SEO обновлены');
    else $smarty->assign("error_message", 'Meta SEO не обновлены');
}
global $meta_seo_item;
global $meta_seo_item_type;
$meta_seo_item = $metaSeo->getMetaItem($meta_seo_item, $meta_seo_item_type);
$smarty->assign("meta_seo_item", $meta_seo_item);
