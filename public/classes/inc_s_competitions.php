<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

include_once('classes/competitions.php');
/* @var competitions $competition */
$competitions = new competitions;

$s = 0;

// get LOCAL /////////////////////////////////////////////////////////////
// NOT needed

// get GROUP /////////////////////////////////////////////////////////
$group = $competitions->getCompetitionPart('group', $s, array('local_id'=>0));
$s++;

$smarty->assign("group_item", $group['item']);
$smarty->assign("group_list", $group['menu']);

// get CHAMPIONSHIP /////////////////////////////////////////////////////////
$championship = $competitions->getCompetitionPart('championship', $s, array('group_id'=>$group['item_id']));
if (!empty($championship['item']) &&
    !empty($championship['item']['ch_settings']) &&
    !empty($championship['item']['ch_settings']['main_url']) &&
    '/'.$championship['item']['ch_settings']['main_url'] != $_SERVER['REQUEST_URI'] &&
    strpos($_SERVER['REQUEST_URI'], '/'.$championship['item']['ch_settings']['main_url']) === false) {
    header('Location: '.$scheme_protocol.$sitepath.$championship['item']['ch_settings']['main_url'], true, 302);
    exit;
}
$s++;

$smarty->assign("championship_list", $championship['menu']);
$smarty->assign("championship_type", $championship['item']['ch_chc_id']);

// get Current type part // #########################################
$current_part_type = $competitions->getCompetitionPartType($s, $championship['item']['ch_chc_id'], $championship['item']['ch_settings']);
$smarty->assign("current_part_type", $current_part_type);

if ($current_part_type == 'championship') {
    $championship['item'] = $competitions->getChampionshipItem($championship['item_id']);
}
$smarty->assign("championship_item", $championship['item']);

// get TOUR /////////////////////////////////////////////////////////
$tour = $competitions->getCompetitionPart('tour', $s, array(
    'type'=>$championship['item']['ch_chc_id'],
    'championship_id'=>$championship['item_id']
));
$s++;

$smarty->assign("tour_item", $tour['item']);
$smarty->assign("tour_list", $tour['menu']);

// get STAGE /////////////////////////////////////////////////////////
$stage = $competitions->getCompetitionPart('stage', $s, array(
    'championship_id'=>$championship['item_id'],
    'type'=>$championship['item']['ch_chc_id'],
    'ch_settings'=>$championship['item']['ch_settings'],
    'tour_id'=>$tour['item_id'],
));
$s++;

$smarty->assign("stage_item", $stage['item']);
$smarty->assign("stage_list", $stage['menu']);

// get COMPETITION ////////////////////////////////////////////////////
$competition = $competitions->getCompetitionPart('competition', $s, array(
    'championship_id'=>$championship['item_id'],
    'tour_id'=>$tour['item_id'],
    'stage_id'=>$stage['item_id'],
));
$s++;
$smarty->assign("competition_item", $competition['item']);
$smarty->assign("competition_list", $competition['menu']);
// get Competition DATA ///////////////////////////////////////////////

$competition_data = $competitions->getCompetitionData($championship['item']['ch_chc_id'], $current_part_type, array(
    'championship_id' => $championship['item_id'],
    'competition_id' => $competition['item_id'],
    'is_rating_table' => $competition['item']['is_rating_table'],
    'competition_list' => $competition['menu'],
    'show_stuff_rating' => (isset($championship['item']['ch_settings']['show_stuff_rating']) ? $championship['item']['ch_settings']['show_stuff_rating'] : 1),
    'championship_count_stuff_rating' => (!empty($championship['item']['ch_settings']['count_stuff_rating']) && $championship['item']['ch_settings']['count_stuff_rating'] > 1) ? $championship['item']['ch_settings']['count_stuff_rating'] : $conf_settings['championship_count_stuff_rating'],
    'table_type_super_15' => ((!empty($championship['item']['ch_settings']['table_type_super_15']))?$championship['item']['ch_settings']['table_type_super_15']:0)
));
$competition_data['staff_json_a'] = array();
if (!empty($competition_data['staff'])) {
    foreach ($competition_data['staff'] as $key=>$item) {
        $competition_data['staff_json_a']['points'][] = array(
            'i' => $key,
            'title' => $item['name'] . ' ' . $item['surname'] .' ' . $item['family'],
            'points' => $item['points'],
            'pop' => $item['pop'],
            'sht' => $item['sht'],
            'pez' => $item['pez'],
            'd_g' => $item['d_g'],
            'y_c' => $item['y_c'],
            'r_c' => $item['r_c'],
        );
        $competition_data['staff_json_a']['championship_id'] = $championship['item_id'];
    }
}
$smarty->assign("competition_staff_json", json_encode($competition_data['staff_json_a'], JSON_UNESCAPED_UNICODE));
$smarty->assign("competition_data", $competition_data);
$smarty->assign("last_round", (!empty($competition_data['round']['last_round']))?$competition_data['round']['last_round']:0);

$smarty->assign("date_key_m", date("Y-m"));

$smarty->assign("live_date_now", strtotime('-3 hours'));
	
// ######################################################################################################

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