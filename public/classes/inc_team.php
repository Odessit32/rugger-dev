<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

include_once('classes/team.php');
$team = new team;

$team_item = false;
$tesm_page = 'profile';

if ($url->rest_path) {
    $s = 0;
    if (!empty($url->rest_path[$s])) {
        $team_item = $team->getTeamItemBySlug($url->rest_path[$s]);
        if (!empty($team_item['address']) && $team_item['address'] != $url->rest_path[$s]) {
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: /team/".$team_item['address']);
            exit();
        }
    }
    $s++;
    if (!empty($url->rest_path[$s])) {
        switch($url->rest_path[$s]) {
            case 'staff':
//                $team_item['staff'] = $team->getTeamStaff($team_item['id']);
                $tesm_page = 'staff';
                break;
            case 'statistics':
                $team_item['statistics'] = $team->getTeamStatistics($team_item['id']);
                $tesm_page = 'statistics';
                break;
            case 'timetable':
                $team_item['timetable'] = $team->getTeamTimetable($team_item['id']);
                $tesm_page = 'timetable';
                break;
            case 'news':
//                $team_item['news'] = $team->getTeamNews($team_item['id']);
                $tesm_page = 'news';
                break;
            case 'photos':
//                $team_item['photos'] = $team->getTeamPhoto($team_item['id']);
                $tesm_page = 'photos';
                break;
            case 'video':
//                $team_item['videos'] = $team->getTeamVideo($team_item['id']);
                $tesm_page = 'videos';
                break;
        }
        $s++;
    }
}
$smarty->assign("tesm_page", $tesm_page);
$smarty->assign("team_item", $team_item);

// meta SEO functions start
if (!empty($team_item)) {
    $meta_seo_item = $team_item['id'];
    $meta_seo_item_type = 'team';
} else {
    $meta_seo_item = $url->page['p_id'];
    $meta_seo_item_type = 'page';
}
include_once('classes/inc_meta_seo.php');
// meta SEO functions finish

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