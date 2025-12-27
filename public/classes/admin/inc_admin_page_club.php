<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('../classes/admin/admin_page_club.php');
$page_club = new page_club;

if($_POST['save_page_club']){
    if ($page_club->savePageClub()) $smarty->assign("ok_message", 'Выбранный клуб сохранен');
    else $smarty->assign("error_message", 'Выбранный клуб не сохранен');
}

$smarty->assign("club_list", $page_club->getClubList());
$smarty->assign("page_club_item", $page_club->getPageClubItem($_GET['item']));
?>