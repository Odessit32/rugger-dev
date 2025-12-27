<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

include_once('../classes/admin/admin_championship.php');
include_once('../classes/admin/admin_photos.php');
include_once('../classes/admin/admin_videos.php');

$championship = new championship;
$photos = new photos;
$videos = new videos;

//
$smarty->assign("city_list", $championship->getCityList());
$smarty->assign("country_list", $championship->getCountryList());
$smarty->assign("champ_list", $championship->getChampList());
//


// ЧЕМПИОНАТ ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////
if(!empty($_POST['add_new_championship'])){
    if ($championship->createChampionship($_POST)) $smarty->assign("ok_message", 'Чемпионат добавлен');
    else $smarty->assign("error_message", 'Чемпионат не добавлен');
}

if(!empty($_POST['save_championship_changes'])){
    if ($championship->updateChampionship($_POST)) {
        $tab = !empty($_POST['active_tab']) ? '&tab=' . urlencode($_POST['active_tab']) : '';
        header('Location: ./?show=championship&get=edit&item=' . intval($_POST['ch_id']) . $tab . '&success=1');
        exit;
    } else {
        $smarty->assign("error_message", 'Чемпионат не обновлен');
    }
}

if(!empty($_POST['delete_championship'])){
    if ($championship->deleteChampionship($_POST['ch_id'])) header('Location: ./?show=championship');
    else $smarty->assign("error_message", 'Чемпионат не удален');
}

if (!empty($_GET['get']) && $_GET['get']=='edit' && !empty($_GET['item'])){
    // Показываем сообщение об успешном сохранении
    if (!empty($_GET['success'])) {
        $smarty->assign("ok_message", 'Чемпионат обновлен');
    }

    $championship_item = $championship->getChampionshipItem($_GET['item']);
    if (empty($championship_item['ch_settings']['table_order_priority'])){
        $table_order_priority = array('z_p_p', 'z_p_t', 'b', 'w_p_v', 'p_p_v', 't_p_v', 'win');
    } else {
        $table_order_priority = explode(',', $championship_item['ch_settings']['table_order_priority']);
        // Добавляем 'win' в конец списка, если его там нет (для совместимости со старыми чемпионатами)
        if (!in_array('win', $table_order_priority)) {
            $table_order_priority[] = 'win';
        }
    }
    $smarty->assign("championship_table_order_priority", $table_order_priority);
    $smarty->assign("championship_item", $championship_item);
}

if (!empty($_GET['get']) && $_GET['get']=='archive'){
    $smarty->assign("championship_list", $championship->getChampionshipList(true));
} else {
    $smarty->assign("championship_list", $championship->getChampionshipList());
}
$smarty->assign("season_list", $championship->getSeasonList());
$smarty->assign("championship_country_list_ne", $championship->getChampionshipCountryListNE());

// ЧЕМПИОНАТ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// СОСТАВ ЧЕМПИОНАТА КОМАНДЫ ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////

if(!empty($_POST['add_championship_team'])){
    // Поддержка множественного добавления команд
    if (!empty($_POST['team_ids']) && strpos($_POST['team_ids'], ',') !== false) {
        // Множественное добавление
        $team_ids = explode(',', $_POST['team_ids']);
        $added_count = 0;
        $failed_count = 0;

        foreach ($team_ids as $team_id) {
            $team_id = intval(trim($team_id));
            if ($team_id > 0) {
                $_POST['team_id'] = $team_id;
                if ($championship->createConnectTCh($_POST)) {
                    $added_count++;
                } else {
                    $failed_count++;
                }
            }
        }

        if ($added_count > 0) {
            $smarty->assign("ok_message", "Добавлено команд: {$added_count}" . ($failed_count > 0 ? ", не добавлено: {$failed_count}" : ''));
        } else {
            $smarty->assign("error_message", 'Ни одна команда не была добавлена');
        }
    } else {
        // Одиночное добавление (для обратной совместимости)
        if (!empty($_POST['team_ids'])) {
            $_POST['team_id'] = intval($_POST['team_ids']);
        }
        if ($championship->createConnectTCh($_POST)) {
            $smarty->assign("ok_message", 'Добавлена новая команда в чемпионат');
        } else {
            $smarty->assign("error_message", 'Чемпионат не пополнен');
        }
    }
}

if(!empty($_POST['edit_championship_team'])){
    if ($championship->updateConnectTCh($_POST)) $smarty->assign("ok_message", 'Обновлена команда чемпионата');
    else $smarty->assign("error_message", 'Не бновлена команда чемпионата');
}

if(!empty($_POST['delete_championship_team'])){
    if ($championship->deleteConnectTCh($_POST)) $smarty->assign("ok_message", 'Удалена запись о команде чемпионата');
    else $smarty->assign("error_message", 'Не удалена запись о команде чемпионата');
}

if (!empty($_GET['team'])){
    $ch_team_item = $championship->getChTItem($_GET['team']);
    $smarty->assign("ch_team_item", $ch_team_item);
}

$smarty->assign("championship_team_list", $championship->getChampionshipTeamList((!empty($_GET['item']))?$_GET['item']:''));

// СОСТАВ ЧЕМПИОНАТА КОМАНДЫ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// РУБРИКИ ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////
//if($_POST['add_new_category']){
//	if ($championship->createCategory($_POST)) $smarty->assign("ok_message", 'Рубрика добавлена');
//	else $smarty->assign("error_message", 'Рубрика не добавлена');
//}

if(!empty($_POST['save_category_changes'])){
    if ($championship->updateCategory($_POST)) $smarty->assign("ok_message", 'Рубрика обновлена');
    else $smarty->assign("error_message", 'Рубрика не обновлена');
}

//if($_POST['delete_category']){
//	if ($championship->deleteCategory($_POST['nс_id'])) header('Location: ./?show=championship&get=categories');
//	else $smarty->assign("error_message", 'Рубрика не удалена');
//}

if (!empty($_GET['get']) && $_GET['get']=='categedit' && !empty($_GET['item'])){
    $category_item = $championship->getCategoryItem($_GET['item']);
    $smarty->assign("category_item", $category_item);
}
$smarty->assign("championship_categories_list_id", $championship->getChampionshipCategoriesListID());
$smarty->assign("championship_categories_list", $championship->getChampionshipCategoriesList());
// РУБРИКИ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// ГРУППЫ ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////
if(!empty($_POST['add_new_group'])){
    if ($championship->createGroup($_POST)) $smarty->assign("ok_message", 'Группа добавлена');
    else $smarty->assign("error_message", 'Группа не добавлена');
}

if(!empty($_POST['save_group_changes'])){
    if ($championship->updateGroup($_POST)) $smarty->assign("ok_message", 'Группа обновлена');
    else $smarty->assign("error_message", 'Группа не обновлена');
}

if(!empty($_POST['delete_group'])){
    if ($championship->deleteGroup($_POST['chg_id'])) header('Location: ./?show=championship&get=group');
    else $smarty->assign("error_message", 'Группа не удалена');
}

if (!empty($_GET['get']) && $_GET['get']=='groupedit' && $_GET['item']>0){
    $group_item = $championship->getGroupItem($_GET['item']);
    $smarty->assign("group_item", $group_item);
}
$smarty->assign("championship_group_list_id", $championship->getChampionshipGroupListID());
$smarty->assign("championship_group_list", $championship->getChampionshipGroupList());
// ГРУППЫ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// ЛОКАЛИЗЦИИ ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////
if(!empty($_POST['add_new_local'])){
    if ($championship->createLocal($_POST)) $smarty->assign("ok_message", 'Локализация группы добавлена');
    else $smarty->assign("error_message", 'Локализация группы не добавлена');
}

if(!empty($_POST['save_local_changes'])){
    if ($championship->updateLocal($_POST)) $smarty->assign("ok_message", 'Локализация группы обновлена');
    else $smarty->assign("error_message", 'Локализация группы не обновлена');
}

if(!empty($_POST['delete_local'])){
    if ($championship->deleteLocal($_POST['chl_id'])) header('Location: ./?show=championship&get=local');
    else $smarty->assign("error_message", 'Локализация группы не удалена');
}

if (!empty($_GET['get']) && $_GET['get']=='localedit' && $_GET['item']>0){
    $group_item = $championship->getLocalItem($_GET['item']);
    $smarty->assign("local_item", $group_item);
}
$smarty->assign("championship_local_list_id", $championship->getChampionshipLocalListID());
$smarty->assign("championship_local_list", $championship->getChampionshipLocalList());
// ЛОКАЛИЗЦИИ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// НАСТРОЙКИ ///////// НАЧАЛО /////////////////////////////////////////////////////////////////
if(!empty($_POST['save_championship_left_active_settings'])){
    if($_POST['is_active']==true) $is_active =1;
    else $is_active = 0;
    $elems = array(
        "set_value" => $is_active,
        "set_datetime_edit" => 'NOW()',
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "is_active_championship_left"
    );
    if ($championship->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if(!empty($_POST['save_championship_left_count_settings'])){
    $_POST['cnv_value'] = intval($_POST['cnv_value']);
    $elems = array(
        "set_value" => $_POST['cnv_value'],
        "set_datetime_edit" => 'NOW()',
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "count_championship_left"
    );
    if ($championship->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if(!empty($_POST['save_championship_count_settings'])){
    $_POST['cnv_value'] = intval($_POST['cnv_value']);
    $elems = array(
        "set_value" => $_POST['cnv_value'],
        "set_datetime_edit" => 'NOW()',
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "count_championship_page"
    );
    if ($championship->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if(!empty($_POST['save_championship_count_stuff_rating'])){
    $_POST['cnv_value'] = intval($_POST['cnv_value']);
    $elems = array(
        "set_value" => $_POST['cnv_value'],
        "set_datetime_edit" => 'NOW()',
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "championship_count_stuff_rating"
    );
    if ($championship->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if (!empty($_GET['get']) && $_GET['get'] == 'settings') {
    $smarty->assign("championship_settings_list", $championship->getChampionshipSettings());

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
            "cnv_author" => USER_ID
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
$championship_id = (!empty($_GET['item']))?intval($_GET['item']):0;
$type = 'championship';

if (!empty($_GET['cont']) && $_GET['cont'] == 4){
    // Photos for championship
    if(!empty($_POST['add_new_championship_photo'])){
        if ($championship->savePhoto($championship_item, $photos)) $smarty->assign("ok_message", 'Фотография добавлена');
        else $smarty->assign("error_message", 'Фотография не добавлена');
    }

    if(!empty($_POST['save_edited_championship_photo'])){
        if ($_POST['ph_type_main']) $photos->resetTypeMainPhotos($_POST['ch_id'], $type);
        if ($photos->saveEditedPhoto($_FILES['file_photo'], $_POST)) $smarty->assign("ok_message", 'Фотография обновлена');
        else $smarty->assign("error_message", 'Фотография не обновлена');
    }

    if(!empty($_POST['delete_edited_championship_photo'])){
        $_GET['item'] = intval($_GET['item']);
        if ($photos->deletePhoto($_POST['ph_id'])) header('Location: ./?show=championship&get=edit&item='.$_GET['item'].'&cont=4');
        else $smarty->assign("error_message", 'Фотография не удалена');
    }

    if (!empty($_GET['g_f_item']) && $_GET['g_f_item']>0){
        $smarty->assign("photo_item", $photos->getPhotoItem($_GET['g_f_item']));
    }

    // Videos for championship
    if(!empty($_POST['add_new_championship_video'])){
        if ($championship->saveVideo($championship_item, $videos)) $smarty->assign("ok_message", 'Видео добавлено');
        else $smarty->assign("error_message", 'Видео не добавлено');
    }

    if(!empty($_POST['save_edited_championship_video'])){
        if ($videos->saveEditedVideo($_POST)) $smarty->assign("ok_message", 'Видео обновлено');
        else $smarty->assign("error_message", 'Видео не обновлено');
    }

    if(!empty($_POST['save_edited_championship_preview'])){
        if ($videos->saveEditedVideoPreview($_FILES['file_photo_preview'], $_POST['v_id'])) $smarty->assign("ok_message", 'Превью для видео обновлено');
        else $smarty->assign("error_message", 'Превью для видео не обновлено');
    }

    if(!empty($_POST['delete_edited_championship_video'])){
        if ($videos->deleteVideo($_POST['v_id'])) header('Location: ./?show=championship&get=edit&item='.$_GET['item'].'&cont=4');
        else $smarty->assign("error_message", 'Видео не удалено');
    }

    if (!empty($_GET['g_v_item']) && $_GET['g_v_item']>0){
        $smarty->assign("video_item", $videos->getVideoItem($_GET['g_v_item']));
    }
    $smarty->assign("championship_photo_gallery", $photos->getTypePhotoGallery($championship_id, $type));
    $smarty->assign("gallery_list", $photos->getPhotoGalleryList());
    $smarty->assign("championship_video_gallery", $videos->getTypeVideoGallery($championship_id, $type));
    $smarty->assign("gallery_list_video", $videos->getVideoGalleryList());
}

$smarty->assign("championship_photo_list", $photos->getTypePhotoList($championship_id, $type));
$smarty->assign("championship_video_list", $videos->getTypeVideoList($championship_id, $type));

// ГАЛЕРЕЯ ////////// КОНЕЦ //////////////////////////////////////////////////////////////////
