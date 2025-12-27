<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('../classes/admin/admin_champ.php');

$champ = new champ;

// СТРАНЫ ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////
if(!empty($_POST['add_new_champ'])){
    if ($champ->createChamp($_POST)) $smarty->assign("ok_message", 'Страна добавлена');
    else $smarty->assign("error_message", 'Страна не добавлена');
}

if(!empty($_POST['save_champ_changes'])){
    if ($champ->updateChamp($_POST)) $smarty->assign("ok_message", 'Страна обновлена');
    else $smarty->assign("error_message", 'Страна не обновлена');
}

if(!empty($_POST['delete_champ'])){
    if ($champ->deleteChamp($_POST['cn_id'])) header('Location: ./?show=team&get=champ');
    else $smarty->assign("error_message", 'Страна не удалена');
}

if (!empty($_GET['get']) && $_GET['get']=='champedit' && !empty($_GET['item'])){
    $champ_item = $champ->getChampItem($_GET['item']);
    $smarty->assign("champ_item", $champ_item);
}

$smarty->assign("team_champ_list_id", $champ->getChampListID());
$smarty->assign("team_champ_list", $champ->getChampList());

// СТРАНЫ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////
