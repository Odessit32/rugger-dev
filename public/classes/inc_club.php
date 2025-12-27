<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('classes/club.php');
$club = new club;

$club_item = false;

$club_item = $club->getClubItem($url->page['p_id']);
$smarty->assign("club_item", $club_item);
if (!empty($url->rest_path[0])) {
    include_once('classes/team.php');
    $team = new team;
    $team_item = $team->getTeamItemUrl($url->rest_path[0]);
    foreach ($club_item['teams'] as &$ti) {
        if ($ti['address'] == $url->rest_path[0]){
            $ti['active'] = 'yes';
        } else {
            $ti['active'] = 'no';
        }
    }
    $smarty->assign("team_item", $team_item);
}
$smarty->assign("team_list", $club_item['teams']);

//    $club->getClubMenu($menu_array, $club_item['cl_id']); // Меню
//    $smarty->assign("menu_array", $menu_array); // Меню + активные страницы

// Баннеры
include_once('classes/banners.php');
$banners = new banners;
$pbi = $banners->getPageBanInfList($url->page['p_id'], $url->page['p_c_banners']);
$smarty->assign("pbi", $pbi);

if ($pbi['classes']) {
    foreach ($pbi['classes'] as $item) {
        if (!empty($item) && file_exists('classes/'.$item)){
            include_once('classes/'.$item);
        }
    }
}