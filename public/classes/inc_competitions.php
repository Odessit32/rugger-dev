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
$local = $competitions->getCompetitionPart('local', $s);
$s++;

$smarty->assign("local_item", $local['item']);
$smarty->assign("local_list", $local['menu']);

// get GROUP /////////////////////////////////////////////////////////
$group = $competitions->getCompetitionPart('group', $s, array('local_id'=>$local['item_id']));
$s++;

$smarty->assign("group_item", $group['item']);
$smarty->assign("group_list", $group['menu']);

// get CHAMPIONSHIP /////////////////////////////////////////////////////////
$championship = $competitions->getCompetitionPart('championship', $s, array('group_id'=>$group['item_id']));
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
    'table_type_super_15' => ((!empty($championship['item']['ch_settings']['table_type_super_15']))?$championship['item']['ch_settings']['table_type_super_15']:0)
));

$smarty->assign("competition_data", $competition_data);
$smarty->assign("last_round", $competition_data['round']['last_round']);

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