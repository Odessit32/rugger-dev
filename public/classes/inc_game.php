<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

include_once('classes/game.php');
$game = new game;

$game_page_mode_menu = array("teams", "time", "report", "live", "info");

$game_page_mode_subpage = false;

$game_page_mode = 'teams';
if ($url->rest_path) {
    $s = 0;
    if ($url->rest_path[$s] && is_numeric($url->rest_path[$s])) {
        $game_id = intval($url->rest_path[$s]);
$game_item = $game->getGameItem($game_id);
if ($game_item === false) {
    header("HTTP/1.0 404 Not Found");
    $smarty->assign("error_message", "Игра не найдена.");
    $smarty->display("error.tpl"); // Предполагаемый шаблон для ошибок
    exit;
}
$smarty->assign("game_item", $game_item);
        
$competition_item = $game->getCompetitionItem($game_item['g_ch_id'], $game_item['g_cp_id']); // выборка информации о соревновании в которой эта игра
        $smarty->assign("competition_item", $competition_item);

        $game_action = $game->getGameActionItem($game_item['g_id'], $game_item['g_owner_t_id'], $game_item['g_guest_t_id']); // Отчет об игре
        $smarty->assign("game_action", $game_action);

        $game_teem_staff = $game->getGamesTeamStaff($game_id);
        if (!empty($game_teem_staff['owner']['main']) && count($game_teem_staff['owner']['main']) >= 15 &&
            !empty($game_teem_staff['guest']['main']) && count($game_teem_staff['guest']['main']) >= 15) {
            $is_teams = true;
        } else {
            $is_teams = false;
            $game_page_mode = 'time';
        }
        $smarty->assign("is_teams", $is_teams);

        $s++;

        // meta SEO functions start
        $meta_seo_item = $game_id;
        $meta_seo_item_type = 'game';
        include_once('classes/inc_meta_seo.php');
        // meta SEO functions finish

        $game_actions = $game->getGamesActions($game_id);
        if (empty($game_actions) && $game_page_mode == 'time') {
            if (!empty($game_item['g_info']->custom_report)) {
                $game_page_mode = 'report';
            } elseif (!empty($game_item['g_info']->live)) {
                $game_page_mode = 'live';
            } else {
                $game_page_mode = 'info';
            }
        }

        include_once('classes/team.php');
        $team = new team;
        $game_teems['owner'] = $team->getTeamStaff($game_item['g_owner_t_id'], $game_id);
        $game_teems['guest'] = $team->getTeamStaff($game_item['g_guest_t_id'], $game_id);
        $smarty->assign("game_teems", $game_teems);
    }
    if (!empty($url->rest_path[$s]) && in_array($url->rest_path[$s], array('small', 'report', 'teams', 'info', 'live', 'big', 'time'))) {
        $game_page_mode = $url->rest_path[$s];
        $game_page_mode_subpage = true;
    }
    $game_page_mode_title = '';
    switch ($game_page_mode) {
        case 'small':
            $game_page_mode = 'small';
            $game_page_mode_title = '';
            break;
        case 'report':
            $game_page_mode = 'report';
            $game_page_mode_title = 'Отчет';
            break;
        case 'teams':
            $game_page_mode = 'teams';
            $game_page_mode_title = 'Команды';

            // Обработка замен для каждого игрока
            if (!empty($game_teem_staff['owner']['main'])) {
                foreach ($game_teem_staff['owner']['main'] as &$s_item) {
                    if (!empty($game_actions['by_staff_s'][$s_item['st_id']]['zam'])) {
                        $s_item['action_zam'] = $game_actions['by_staff_s'][$s_item['st_id']]['zam'];
                    } else {
                        $s_item['action_zam'] = false;
                    }
                }
            }
            if (!empty($game_teem_staff['owner']['zam'])) {
                foreach ($game_teem_staff['owner']['zam'] as &$s_item) {
                    if (!empty($game_actions['by_staff_s'][$s_item['st_id']]['zam'])) {
                        $s_item['action_zam'] = $game_actions['by_staff_s'][$s_item['st_id']]['zam'];
                    } else {
                        $s_item['action_zam'] = false;
                    }
                }
            }
            if (!empty($game_teem_staff['owner']['zam_all'])) {
                foreach ($game_teem_staff['owner']['zam_all'] as &$s_item) {
                    if (!empty($game_actions['by_staff_s'][$s_item['st_id']]['zam'])) {
                        $s_item['action_zam'] = $game_actions['by_staff_s'][$s_item['st_id']]['zam'];
                    } else {
                        $s_item['action_zam'] = false;
                    }
                }
            }
            if (!empty($game_teem_staff['guest']['main'])) {
                foreach ($game_teem_staff['guest']['main'] as &$s_item) {
                    if (!empty($game_actions['by_staff_s'][$s_item['st_id']]['zam'])) {
                        $s_item['action_zam'] = $game_actions['by_staff_s'][$s_item['st_id']]['zam'];
                    } else {
                        $s_item['action_zam'] = false;
                    }
                }
            }
            if (!empty($game_teem_staff['guest']['zam'])) {
                foreach ($game_teem_staff['guest']['zam'] as &$s_item) {
                    if (!empty($game_actions['by_staff_s'][$s_item['st_id']]['zam'])) {
                        $s_item['action_zam'] = $game_actions['by_staff_s'][$s_item['st_id']]['zam'];
                    } else {
                        $s_item['action_zam'] = false;
                    }
                }
            }
            if (!empty($game_teem_staff['guest']['zam_all'])) {
                foreach ($game_teem_staff['guest']['zam_all'] as &$s_item) {
                    if (!empty($game_actions['by_staff_s'][$s_item['st_id']]['zam'])) {
                        $s_item['action_zam'] = $game_actions['by_staff_s'][$s_item['st_id']]['zam'];
                    } else {
                        $s_item['action_zam'] = false;
                    }
                }
            }
            $smarty->assign("game_teem_staff", $game_teem_staff);

            break;
        case 'info':
            $game_page_mode = 'info';
            $game_page_mode_title = 'Инфо';
            break;
        case 'live':
            $game_page_mode = 'live';
            $game_page_mode_title = 'Live';
            break;
        case 'time':
            $game_page_mode = 'time';
            $game_page_mode_title = 'Хронология';
            break;
    }
    $smarty->assign("game_actions", $game_actions);
}
if (empty($game_item)) {
    // meta SEO functions start
    $meta_seo_item = $url->page['p_id'];
    $meta_seo_item_type = 'page';
    include_once('classes/inc_meta_seo.php');
    // meta SEO functions finish
}
$smarty->assign("game_page_mode", $game_page_mode);

/** meta data for game page */
// Определяем $current_page_item_data на основе $url->page
$current_page_item_data = isset($url->page) ? $url->page : ['adress' => ''];

$game_meta = array(
    'h1' => '',
    'title' => '',
    'decription' => '',
    'is_canonical' => false,
    'canonical_url' => (isset($current_page_item_data['adress']) ? $current_page_item_data['adress'] : '') . '/' . (isset($game_id) ? $game_id : '') // Исправлено: добавлена проверка на существование переменных
);

if (!empty($game_item)) {
    if (!empty($competition_item['championship']['chg_title'])) {
        $game_meta['h1'] .= '«'.$competition_item['championship']['chg_title'].'», ';
    }
    $game_meta['h1'] .= '«'.$competition_item['championship']['title'].'», ';
    $game_meta['h1'] .= '«'.$game_item['owner']['title'].'» - «'.$game_item['guest']['title'].'»';
    $game_meta['h1'] .= !empty($game_page_mode_title) && $game_page_mode_subpage ? ' «' . $game_page_mode_title . '»' : '';
    $game_meta['h1'] .= ' ' . date("d", strtotime($game_item['g_date_schedule'])).' ';
    $game_meta['h1'] .= $month[date("m", strtotime($game_item['g_date_schedule']))].' ';
    $game_meta['h1'] .= date("Y", strtotime($game_item['g_date_schedule']));

    $game_meta['title'] = '«'.$game_item['owner']['title'].'» - «'.$game_item['guest']['title'].'»';
    if (!empty($game_item['g_is_done']) && $game_item['g_is_done'] == 'yes') {
        $game_meta['title'] .= ' ('.$game_item['g_owner_points'].' : '.$game_item['g_guest_points'].')';
    }
    $game_meta['title'] .= ', регби,';
    if (!empty($competition_item['championship']['chg_title'])) {
        $game_meta['title'] .= ' '.$competition_item['championship']['chg_title'].' по регби, ';
    }
    $game_meta['title'] .= !empty($game_page_mode_title) && $game_page_mode_subpage ? '«' . $game_page_mode_title . '» ' : '';
    $game_meta['title'] .= date("d", strtotime($game_item['g_date_schedule'])).' ';
    $game_meta['title'] .= $month[date("m", strtotime($game_item['g_date_schedule']))].' ';
    $game_meta['title'] .= date("Y", strtotime($game_item['g_date_schedule']));
    $game_meta['title'] .= ' - '.$conf_vars['title'];

    $game_meta['decription'] .= !empty($game_page_mode_title) && $game_page_mode_subpage ? '' . $game_page_mode_title . ' матча ' : '';
    $game_meta['decription'] .= '«'.$game_item['owner']['title'].'» - «'.$game_item['guest']['title'].'»';
    if (!empty($game_item['g_is_done']) && $game_item['g_is_done'] == 'yes') {
        $game_meta['decription'] .= ' ('.$game_item['g_owner_points'].' : '.$game_item['g_guest_points'].')';
    }
    $game_meta['decription'] .= ', регбийный матч, ';
    $game_meta['decription'] .= date("d", strtotime($game_item['g_date_schedule'])).' ';
    $game_meta['decription'] .= $month[date("m", strtotime($game_item['g_date_schedule']))].', ';
    if (!empty($competition_item['championship']['chg_title'])) {
        $game_meta['decription'] .= ' «'.$competition_item['championship']['chg_title'].'», ';
    }
    $game_meta['decription'] .= ' «'.$competition_item['championship']['title'].'»';
    if (!empty($competition_item['competition']['title'])
        && $competition_item['competition']['title'] != $competition_item['championship']['chg_title']) {
        $game_meta['decription'] .= ', «'.$competition_item['competition']['title'].'»';
    }

    $meta_subpages = array();

    if ($is_teams) {
        $meta_subpages[] = 'teams';
    }
    if (!empty($game_actions)) {
        $meta_subpages[] = 'time';
    }
    if (!empty($game_item['g_info']->custom_report)) {
        $meta_subpages[] = 'report';
    }
    if (!empty($game_item['g_info']->live)) {
        $meta_subpages[] = 'live';
    }
    if (!empty($game_item['g_info']->town) || !empty($game_item['g_info']->stadium) || !empty($game_item['g_info']->viewers) ||
        !empty($game_item['g_info']->main_judge) || !empty($game_item['g_info']->side_referee) || !empty($game_item['g_info']->video_referee)) {
        $meta_subpages[] = 'info';
    }

    if (count($url->rest_path) > 1) {
        if ($meta_subpages[0] == $game_page_mode) {
            $game_meta['is_canonical'] = true;
        }
    }
}

$smarty->assign("game_meta", $game_meta);

// Баннеры
include_once('classes/banners.php');
$banners = new banners;
$pbi = $banners->getPageBanInfList($url->page['p_id'], $url->page['p_c_banners']);
$smarty->assign("pbi", $pbi);

if ($pbi['classes']) {
    foreach ($pbi['classes'] as $item) {
        if (!empty($item) && file_exists('classes/'.$item)) {
            include_once('classes/'.$item);
        }
    }
}
?>