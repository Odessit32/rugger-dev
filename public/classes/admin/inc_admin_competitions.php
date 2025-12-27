<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('../classes/admin/admin_competitions.php');

$competitions = new competitions;

// ТУРЫ ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////

if(!empty($_POST['add_new_tour'])){
    if ($competitions->createTour($_POST['ch_id'])) $smarty->assign("ok_message", 'Тур добавлен');
    else $smarty->assign("error_message", 'Тур не добавлен');
}

if (!empty($_POST['submitsaveteamtourcount'])) {
    if ($competitions->saveTourTeamCountPoints($_POST)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

// ТУРЫ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// СОСТАВ КОМАНДЫ ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////

if(!empty($_POST['save_owner_st']) || !empty($_POST['save_guest_st'])){
    if ($competitions->saveConnectGSt()) $smarty->assign("ok_message", 'Список команды для игры сохранен');
    else $smarty->assign("error_message", 'Список команды для игры не сохранен');
}

if(!empty($_POST['clear_owner_st']) || !empty($_POST['clear_guest_st'])){
    if ($competitions->clearConnectGSt($_POST['t_id'], $_POST['g_id'])) $smarty->assign("ok_message", 'Список команды для игры удален');
    else $smarty->assign("error_message", 'Список команды для игры не удален');
}
if (empty($_GET['app_type']) || $_GET['app_type'] == 'player') $app_type = 'player';
if (!empty($_GET['app_type']) && $_GET['app_type'] == 'head') $app_type = 'head';
if (!empty($_GET['app_type']) && $_GET['app_type'] == 'rest') $app_type = 'rest';
$smarty->assign("app_type", $app_type);

// СОСТАВ КОМАНДЫ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// ИГРЫ ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////

if(!empty($_POST['add_new_game'])){
    if ($competitions->createGame($_POST)) $smarty->assign("ok_message", 'Игра добавлена');
    else $smarty->assign("error_message", 'Игра не добавлена');
}

if(!empty($_POST['save_game_changes'])){
    if ($competitions->updateGame($_POST)) $smarty->assign("ok_message", 'Игра обновлена');
    else $smarty->assign("error_message", 'Игра не обновлена');
}

if(!empty($_POST['delete_game'])){
    if ($competitions->deleteGame($_POST['g_id'])) header('Location: ./?show=competitions&ch='.$_GET['ch'].'&get='.$_GET['get'].'&item='.$_GET['item']);
    else $smarty->assign("error_message", 'Игра не удалена');
}

if (!empty($_GET['item']) && !empty($_GET['edit_comp']) && $_GET['edit_comp']=='games_list'){
    $sort = !empty($_GET['sort'])?$_GET['sort']:'';
    $sort_order = !empty($_GET['sort_order'])?$_GET['sort_order']:'';
    $games_list = $competitions->getGamesList($_GET['item'], $sort, $sort_order);
    $smarty->assign("games_list", $games_list);
}

if (!empty($_GET['item']) && !empty($_GET['edit_comp']) && $_GET['edit_comp']=='games_edit'){
    $game_item = $competitions->getGameItem($_GET['g_item']);
    $smarty->assign("game_item", $game_item);
    $smarty->assign("team_categories_list", $competitions->getTeamCategoriesList());
    $smarty->assign("team_categories_list_id", $competitions->getTeamCategoriesListID());
    $smarty->assign("competition_team", $competitions->getCompetitionsTeamList($_GET['item'], $_GET['ch']));
}

$smarty->assign("country_list", $competitions->getCountryList()); // Список стран
$smarty->assign("city_list", $competitions->getCityList()); // Список городов
$smarty->assign("stadium_list", $competitions->getStadiumList()); // Список стадионов

// ИГРЫ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// СОРЕВНОВАНИЯ ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////

if(!empty($_POST['add_new_comp'])){
    if ($competitions->createCompetitions($_POST)) $smarty->assign("ok_message", 'Соревнование добавлено');
    else $smarty->assign("error_message", 'Соревнование не добавлено');
}

if(!empty($_POST['save_comp_changes'])){
    if ($competitions->updateCompetitions($_POST)) $smarty->assign("ok_message", 'Соревнование обновлено');
    else $smarty->assign("error_message", 'Соревнование не обновлено');
}

if(!empty($_POST['delete_comp'])){
    if ($competitions->deleteCompetitions($_POST['cp_id'])) header('Location: ./?show=competitions&ch='.$_GET['ch']);
    else $smarty->assign("error_message", 'Соревнование не удален');
}

if (!empty($_GET['get']) && ($_GET['get'] == 'add' || $_GET['get'] == 'addmanual')){
    $search = array("'", '"');
    $replace = array('', '');
    $postdata['ch'] = !empty($_POST['ch'])?intval($_POST['ch']):0;
    $postdata['ch_stage'] = !empty($_POST['ch_stage'])?intval($_POST['ch_stage']):0;
    for ($i=1; $i<=$postdata['ch_stage']; $i++){
        $postdata['ch_competitions'][$i] = intval($_POST['ch_competitions_'.$i]);
        for ($j=1; $j<=$postdata['ch_competitions'][$i]; $j++){
            $postdata['cp_title'][$i][$j] = str_replace($search, $replace, $_POST['cp_title_'.$i.'-'.$j]);
            //$postdata['cp_parent'][$i][$j] = str_replace($search, $replace, $_POST['cp_parent_'.$i.'-'.$j]);
            /*$postdata['cp_team'][$i][$j] = $_POST['cp_team_'.$i.'-'.$j];*/
            $cp_team = false;
            if ($_POST['cp_team_'.$i.'-'.$j])
                foreach($_POST['cp_team_'.$i.'-'.$j] as $item) {
                    $cp_item['ex'] = explode('-', $item);
                    $cp_item['title'] = $item;
                    $cp_team[] = $cp_item;
                }
            $postdata['cp_team'][$i][$j] = $cp_team;
        }
    }
    $smarty->assign("postdata", $postdata);
}

if(!empty($_POST['save_structure'])){
    if ($competitions->SaveStructure($_POST)) $smarty->assign("ok_message", 'Структура чемпионата сохранена');
    else $smarty->assign("error_message", 'Структура чемпионата не сохранена');
}

if(!empty($_POST['save_structure_games'])){
    if ($competitions->SaveStructureGames($_POST)) header('Location: ./?show=competitions&ch='.$_GET['ch']);
    else $smarty->assign("error_message", 'Игры чемпионата не сохранены');
}

$tour = !empty($_GET['tour'])?intval($_GET['tour']):0;

if (!empty($_GET['item']) && $_GET['get']=='edit'){
    $competitions_item = $competitions->getCompetitionsItem($_GET['item']);
    $smarty->assign("competitions_item", $competitions_item);
    $tour = $competitions_item['cp_tour'];
}

if(!empty($_POST['stage_title_save'])){
    if ($competitions->saveStageTitle($_POST)) $smarty->assign("ok_message", 'Соревнование обновлено (название этапа)');
    if ($competitions->saveStageTitle($_POST)) $smarty->assign("ok_message", 'Соревнование обновлено (название этапа)');
    else $smarty->assign("error_message", 'Соревнование не обновлено (название этапа)');
    if ($competitions->saveStageDate($_POST)) $smarty->assign("ok_message", 'Соревнование обновлено (дата этапа)');
    else $smarty->assign("error_message", 'Соревнование не обновлено (дата этапа)');
    if ($competitions->saveStageIsOnePage($_POST)) $smarty->assign("ok_message", 'Соревнование обновлено (настройки отображения соревнований)');
    else $smarty->assign("error_message", 'Соревнование не обновлено (настройки отображения соревнований)');
    if ($competitions->saveStageIsShowDate($_POST)) $smarty->assign("ok_message", 'Соревнование обновлено (настройки отображения соревнований)');
    else $smarty->assign("error_message", 'Соревнование не обновлено (настройки отображения соревнований)');
}

if(!empty($_POST['save_tour_title'])){
    if ($competitions->saveTourTitle($_POST)) $smarty->assign("ok_message", 'Соревнование обновлено (название тура)');
    else $smarty->assign("error_message", 'Соревнование не обновлено (название тура)');
    if ($competitions->saveTourDate($_POST)) $smarty->assign("ok_message", 'Соревнование обновлено (дата тура)');
    else $smarty->assign("error_message", 'Соревнование не обновлено (дата тура)');
}

if (!empty($_GET['ch'])){
    $_GET['ch'] = intval($_GET['ch']);
    $championship_item = $competitions->getChampionshipItem($_GET['ch']);
    $smarty->assign("championship_item", $championship_item);
    $smarty->assign("championship_team", $competitions->getChampionshipTeam($_GET['ch']));
    $smarty->assign("championship_team_id", $competitions->getChampionshipTeamId($_GET['ch']));
    if ($championship_item['ch_chc_id'] != 2) $championship_item['ch_tours'] = 0;
    $smarty->assign("competitions_list_tour", $competitions->getCompetitionsListTour($championship_item['ch_id'], $championship_item['ch_tours']));
    $smarty->assign("competitions_list_form", $competitions->getCompetitionsListForm($championship_item['ch_id'], $tour));
    $smarty->assign("competitions_list", $competitions->getCompetitionsList($tour)); // проверить нужно ли еще?
} else {
    $smarty->assign("championship_item", false);
}

if (!empty($_GET['get']) && $_GET['get']=='archive'){
    $smarty->assign("championship_list", $competitions->getChampionshipList(true));
} else {
    $smarty->assign("championship_list", $competitions->getChampionshipList());
}
$smarty->assign("season_list", $competitions->getSeasonList());
$smarty->assign("championship_country_list_ne", $competitions->getChampionshipCountryListNE());


// СОРЕВНОВАНИЯ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// ЗАЯВКИ ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////
if (!empty($_GET['get']) && $_GET['get']=='request') {
    if(!empty($_POST['clear_request'])){
        if ($competitions->clearRequest($_POST['ch_id'], $_POST['tour'], $_POST['t_id'])) $smarty->assign("ok_message", 'Заявка удалена');
        else $smarty->assign("error_message", 'Заявка не удалена');
    }

    if(!empty($_POST['save_request'])){
        if ($competitions->saveRequest()) $smarty->assign("ok_message", 'Заявка сохранена');
        else $smarty->assign("error_message", 'Заявка не сохранена');
    }

    if (!empty($_GET['item'])){
        $request_item = $competitions->getRequestItem($championship_item['ch_id'], $tour, $_GET['item']);
        $smarty->assign("request_item", $request_item);
    }

    $smarty->assign("team_categories_list", $competitions->getTeamCategoriesList());
    $smarty->assign("request_list", $competitions->getRequestList($championship_item['ch_id'], $tour));
}

// ЗАЯВКИ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////
