<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('../classes/admin/admin_games.php');
include_once('../classes/admin/admin_photos.php');
include_once('../classes/admin/admin_videos.php');

$games = new games;
$photos = new photos;
$videos = new videos;

// ИГРЫ ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////

if(!empty($_POST['delete_game'])){
    if ($games->deleteGame($_POST['g_id'])) header('Location: ./?show=games');
    else $smarty->assign("error_message", 'Игра не удалена');
}

if(!empty($_POST['clear_report'])){
    if ($games->clearGameReport($_POST['g_id'], $_POST['t_id'])) $smarty->assign("ok_message", 'Отчет очищен');
    else $smarty->assign("error_message", 'Отчет не очищен');
}

// add_g_action and edit_g_action are handled early in index.php with PRG pattern

if(!empty($_POST['save_games_changes'])){
    if ($games->updateGames($_POST)) $smarty->assign("ok_message", 'Игра обновлена');
    else $smarty->assign("error_message", 'Игра не обновлена');
}

if(!empty($_POST['save_games_report_short'])){
    if ($games->saveReportShort($_POST)) $smarty->assign("ok_message", 'Короткий отчет сохранен');
    else $smarty->assign("error_message", 'Короткий отчет не сохранен');
}

if(!empty($_POST['save_games_report_extra'])){
    if ($games->saveReportExtra($_POST)) $smarty->assign("ok_message", 'Дополнительные настройки сохранены');
    else $smarty->assign("error_message", 'Дополнительные настройки не сохранены');
}

if(!empty($_POST['save_games_info'])){
    if ($games->saveGameInfo($_POST)) $smarty->assign("ok_message", 'Дополнительные настройки сохранены');
    else $smarty->assign("error_message", 'Дополнительные настройки не сохранены');
}

if(!empty($_POST['save_report'])){
    if ($games->saveReport($_POST['g_id'], $_POST['re_count_points'])) $smarty->assign("ok_message", 'Отчет сохранен');
    else $smarty->assign("error_message", 'Отчет не сохранен');
}

if(!empty($_POST['return_report'])){
    if ($games->returnReport($_POST['g_id'])) $smarty->assign("ok_message", 'Отчет возвращен для редактирования');
    else $smarty->assign("error_message", 'Отчет не возвращен для редактирования');
}

if (!empty($_GET['get']) && $_GET['get']=='edit' && !empty($_GET['item'])){
    $games_item = $games->getGamesItem(intval($_GET['item']));
    $smarty->assign("games_item", $games_item);

    // meta SEO functions start
    $meta_seo_item = $games_item['g_id'];
    $meta_seo_item_type = 'game';
    include_once('../classes/admin/inc_admin_meta_seo.php');
    // meta SEO functions finish
}

$smarty->assign("championship_list", $games->getChampionshipList()); // список чемпионатов

$smarty->assign("games_list", $games->getGamesList(intval(!empty($_GET['page'])?$_GET['page']:0), 20, intval(!empty($_GET['ch'])?$_GET['ch']:0))); // список игр
$smarty->assign("games_pages", $games->getGamesPages(intval(!empty($_GET['page'])?$_GET['page']:0), 20, intval(!empty($_GET['ch'])?$_GET['ch']:0))); // страницы

try {
    $smarty->assign("g_t_staff", $games->getGamesTeamStaff(intval(!empty($_GET['item'])?$_GET['item']:0))); // список игроков для игры
    $smarty->assign("g_action", $games->getGamesAction(intval(!empty($_GET['item'])?$_GET['item']:0))); // список действий игроков для отчета
} catch (Error $e) {
    error_log('getGamesTeamStaff/getGamesAction Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    $smarty->assign("error_message", 'Ошибка загрузки данных: ' . $e->getMessage());
}

// ИГРЫ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// НАСТРОЙКИ ///////// НАЧАЛО /////////////////////////////////////////////////////////////////
if(!empty($_POST['save_games_left_active_settings'])){
    if($_POST['is_active']==true) $is_active =1;
    else $is_active = 0;
    $elems = array(
        "set_value" => $is_active,
        "set_datetime_edit" => 'NOW()',
        "set_author" => (defined('USER_ID') ? USER_ID : 0)
    );
    $condition = array(
        "set_name" => "is_active_games_left"
    );
    if ($games->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if(!empty($_POST['save_games_left_count_settings'])){
    $_POST['cnv_value'] = intval($_POST['cnv_value']);
    $elems = array(
        "set_value" => $_POST['cnv_value'],
        "set_datetime_edit" => 'NOW()',
        "set_author" => (defined('USER_ID') ? USER_ID : 0)
    );
    $condition = array(
        "set_name" => "count_games_left"
    );
    if ($games->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if(!empty($_POST['save_games_count_settings'])){
    $_POST['cnv_value'] = intval($_POST['cnv_value']);
    $elems = array(
        "set_value" => $_POST['cnv_value'],
        "set_datetime_edit" => 'NOW()',
        "set_author" => (defined('USER_ID') ? USER_ID : 0)
    );
    $condition = array(
        "set_name" => "count_games_page"
    );
    if ($games->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if (!empty($_GET['get']) && $_GET['get'] == 'settings') {
    $smarty->assign("games_settings_list", $games->getGamesSettings());

    // Заголовки ////////////////////////////////////////////////////////////////
    include_once('../classes/admin/admin_conf_vars.php');
    $conf = new conf_vars;

    if (!empty($_POST['save_var'])){
        $search = array("\\"."'", "\\".'"', "'", '"', '<p>', '</p>');
        $replace = array('', '', '', '', '', '');
        $_POST['cnv_value'] = str_replace($search, $replace, $_POST['cnv_value']);

        $elems = array(
            "cnv_value" => $_POST['cnv_value'],
            "cnv_datetime_edit" => 'NOW()',
            "cnv_author" => (defined('USER_ID') ? USER_ID : 0)
        );
        if ($_POST['lang'] == 'rus') $lang = 'rus';
        if ($_POST['lang'] == 'ukr') $lang = 'ukr';
        if ($_POST['lang'] == 'eng') $lang = 'eng';
        $condition = array(
            "cnv_lang" => $lang,
            "cnv_name" => $_POST['cnv_name']
        );
        if ($conf->saveVarTitle($elems, $condition)) $ok_message .= "Переменная сайта обновлена ($lang)\n";
        else $error_message .= "Переменная сайта не обновлен ($lang)\n";
    }

    $smarty->assign("conf_vars", $conf->getConfVars());
}
// НАСТРОЙКИ ///////// КОНЕЦ /////////////////////////////////////////////////////////////////

// ГАЛЕРЕЯ ////////// НАЧАЛО /////////////////////////////////////////////////////////////////
$game_id = !empty($_GET['item'])?intval($_GET['item']):0;
$type = 'game';

if (!empty($_GET['cont']) && $_GET['cont'] == 4){
    // Photos for games
    if(!empty($_POST['add_new_games_photo'])){
        if ($games->savePhoto($games_item, $photos)) $smarty->assign("ok_message", 'Фотография добавлена');
        else $smarty->assign("error_message", 'Фотография не добавлена');
    }

    if(!empty($_POST['save_edited_games_photo'])){
        if ($_POST['ph_type_main']) $photos->resetTypeMainPhotos($_POST['n_id'], $type);
        if ($photos->saveEditedPhoto($_FILES['file_photo'], $_POST)) $smarty->assign("ok_message", 'Фотография обновлена');
        else $smarty->assign("error_message", 'Фотография не обновлена');
    }

    if(!empty($_POST['delete_edited_games_photo'])){
        $_GET['item'] = intval($_GET['item']);
        if ($photos->deletePhoto($_POST['ph_id'])) header('Location: ./?show=games&get=edit&item='.$_GET['item'].'&cont=4');
        else $smarty->assign("error_message", 'Фотография не удалена');
    }

    if (!empty($_GET['g_f_item'])){
        $smarty->assign("photo_item", $photos->getPhotoItem($_GET['g_f_item']));
    }

    // Videos for games
    if(!empty($_POST['add_new_games_video'])){
        if ($games->saveVideo($games_item, $videos)) $smarty->assign("ok_message", 'Видео добавлено');
        else $smarty->assign("error_message", 'Видео не добавлено');
    }

    if(!empty($_POST['save_edited_games_video'])){
        if ($videos->saveEditedVideo($_POST)) $smarty->assign("ok_message", 'Видео обновлено');
        else $smarty->assign("error_message", 'Видео не обновлено');
    }

    if(!empty($_POST['save_edited_games_preview'])){
        if ($videos->saveEditedVideoPreview($_FILES['file_photo_preview'], $_POST['v_id'])) $smarty->assign("ok_message", 'Превью для видео обновлено');
        else $smarty->assign("error_message", 'Превью для видео не обновлено');
    }

    if(!empty($_POST['delete_edited_games_video'])){
        if ($videos->deleteVideo($_POST['v_id'])) header('Location: ./?show=games&get=edit&item='.$_GET['item'].'&cont=4');
        else $smarty->assign("error_message", 'Видео не удалено');
    }

    if (!empty($_GET['g_v_item'])){
        $smarty->assign("video_item", $videos->getVideoItem($_GET['g_v_item']));
    }
    $smarty->assign("games_photo_gallery", $photos->getTypePhotoGallery($game_id, $type));
    $smarty->assign("gallery_list", $photos->getPhotoGalleryList());
    $smarty->assign("games_video_gallery", $videos->getTypeVideoGallery($game_id, $type));
    $smarty->assign("gallery_list_video", $videos->getVideoGalleryList());
}

$smarty->assign("games_photo_list", $photos->getTypePhotoList($game_id, $type));
$smarty->assign("games_video_list", $videos->getTypeVideoList($game_id, $type));

// ГАЛЕРЕЯ ////////// КОНЕЦ //////////////////////////////////////////////////////////////////
?>