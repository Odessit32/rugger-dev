<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('../classes/admin/admin_page_section_country.php');
$page_country = new PageCountryExtra;

if($_POST['save_page_country']){
    if ($page_country->savePageExtra()) $smarty->assign("ok_message", 'Выбраннная страна сохранена');
    else $smarty->assign("error_message", 'Выбранная страна не сохранена');
}

$smarty->assign("country_list", $page_country->getCountryList());
$smarty->assign("page_extra_item", $page_country->getPageExtraItem($_GET['item']));
