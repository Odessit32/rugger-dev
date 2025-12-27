<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('../classes/admin/admin_page_section_championship.php');
$page_championship = new PageChampionshipExtra;

if(!empty($_POST['save_page_championship'])){
    if ($page_championship->savePageExtra()) $smarty->assign("ok_message", 'Выбранный чемпионат сохранен');
    else $smarty->assign("error_message", 'Выбранный чемпионат не сохранен');
}

$smarty->assign("champ_list", $page_championship->getChampList());
$smarty->assign("page_extra_item", $page_championship->getPageExtraItem($_GET['item']));
