<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('../classes/admin/admin_page_team.php');
$page_team = new page_team;

if($_POST['save_page_team']){
    if ($page_team->savePageTeam()) $smarty->assign("ok_message", 'Выбранная команда сохранена');
    else $smarty->assign("error_message", 'Выбранная команда не сохранена');
}

$smarty->assign("team_list", $page_team->getTeamList());
$smarty->assign("page_team_item", $page_team->getPageTeamItem($_GET['item']));
?>