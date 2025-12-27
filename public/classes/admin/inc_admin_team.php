<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once('../classes/admin/admin_team.php');
include_once('../classes/admin/admin_photos.php');
include_once('../classes/admin/admin_videos.php');
include_once('../classes/admin/inc_admin_champ.php');
include_once('../classes/admin/admin_conf_vars.php');

$team = new team;
$photos = new photos;
$videos = new videos;
$conf = new conf_vars;

// Загружаем conf_vars для использования в шаблонах
$smarty->assign("conf_vars", $conf->getConfVars());

// КОМАНДЫ ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////
if (!empty($_POST['add_new_team'])) {
    // Проверка обязательных полей
    if (empty($team)) {
        $smarty->assign("error_message", 'Ошибка инициализации объекта команды');
    } elseif (empty($_POST['t_title_ru']) && empty($_POST['t_title_ua']) && empty($_POST['t_title_en'])) {
        $smarty->assign("error_message", 'Укажите название команды хотя бы на одном языке');
    } else {
        if ($team->createTeam($_POST)) {
            $smarty->assign("ok_message", 'Команда добавлена');
        } else {
            $smarty->assign("error_message", 'Команда не добавлена. Подробности в логе ошибок.');
        }
    }
}

if (!empty($_POST['save_team_changes'])) {
    if ($team->updateTeam($_POST)) $smarty->assign("ok_message", 'Команда обновлена');
    else $smarty->assign("error_message", 'Команда не обновлена');
}

if (!empty($_POST['delete_team'])) {
    if ($team->deleteTeam($_POST['t_id'])) header('Location: ./?show=team');
    else $smarty->assign("error_message", 'Команда не удалена');
}

// Объединение команд (merge)
if (!empty($_POST['merge_team'])) {
    $target_id = intval($_POST['t_id']);
    $source_id = intval($_POST['merge_source_id']);
    $result = $team->mergeTeam($target_id, $source_id);
    if ($result['success']) {
        $smarty->assign("ok_message", 'Команды объединены: ' . $result['source'] . ' → ' . $result['target'] . '<br>' . implode('<br>', $result['merged']));
    } else {
        $smarty->assign("error_message", 'Ошибка объединения: ' . $result['error']);
    }
}

if (!empty($_POST['save_team_general_appointment_show'])) {
    if ($team->saveTeamInfo($_POST)) $smarty->assign("ok_message", 'Команда обновлена');
    else $smarty->assign("error_message", 'Команда не обновлена');
}

if (!empty($_POST['save_team_general_tab_titles'])) {
    if ($team->saveTeamInfo($_POST)) $smarty->assign("ok_message", 'Команда обновлена');
    else $smarty->assign("error_message", 'Команда не обновлена');
}

if (!empty($_GET['get']) && $_GET['get'] == 'edit' && !empty($_GET['item'])) {
    $team_item = $team->getTeamItem($_GET['item']);
    $smarty->assign("team_item", $team_item);

    // ОПТИМИЗАЦИЯ: Загружаем данные галереи
    // Фото и видео команды загружаются всегда (небольшой объём)
    // Списки ВСЕХ галерей (67,922 + 12,719) загружаются только при необходимости
    $team_photo_list = $photos->getTypePhotoList($_GET['item'], 'team');
    $smarty->assign("team_photo_list", $team_photo_list);

    $team_photo_gallery = $photos->getTypePhotoGallery($_GET['item'], 'team');
    $smarty->assign("team_photo_gallery", $team_photo_gallery);

    $team_video_list = $videos->getTypeVideoList($_GET['item'], 'team');
    $smarty->assign("team_video_list", $team_video_list);

    // Загружаем списки ВСЕХ галерей только для форм добавления/редактирования
    $needs_gallery_lists = !empty($_GET['g_get']) || !empty($_GET['g_f_item']) || !empty($_GET['g_v_item']);
    if ($needs_gallery_lists) {
        $gallery_list = $photos->getPhotoGalleryList();
        $smarty->assign("gallery_list", $gallery_list);

        $gallery_list_video = $videos->getVideoGalleryList();
        $smarty->assign("gallery_list_video", $gallery_list_video);

        $video_category_list = $videos->getVideoCategoryList();
        $smarty->assign("video_category_list", $video_category_list);
    }

    // meta SEO functions start
    $meta_seo_item = $team_item['t_id'];
    $meta_seo_item_type = 'team';
    include_once('../classes/admin/inc_admin_meta_seo.php');
    // meta SEO functions finish
}

// ОПТИМИЗАЦИЯ: Загружаем списки команд только когда НЕ редактируем конкретную команду
// Эти списки нужны только для главной страницы со списком всех команд
if (empty($_GET['get']) || $_GET['get'] != 'edit') {
    $smarty->assign("team_list", $team->getTeamList());
    $smarty->assign("team_country_list_ne", $team->getTeamCountryListNE());
    $smarty->assign("team_filter_list", $team->getTeamFilterList());
}

// КОМАНДЫ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// СОСТАВ КОМАНДЫ ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////

// Добавление нового члена команды
if (!empty($_POST['add_team_staff'])) {
    if ($team->createConnectStT($_POST)) {
        // Редирект после успешного добавления (Post/Redirect/Get pattern)
        header('Location: ./?show=team&get=edit&item='.$_POST['t_id'].'&cont=5'.(!empty($_POST['app_type']) ? '&app_type='.$_POST['app_type'] : ''));
        exit;
    } else {
        $smarty->assign("error_message", 'Команда не пополнена');
    }
}

// Добавить новую должность существующему члену команды
if (!empty($_POST['add_new_team_staff'])) {
    if ($team->createNewConnectStT($_POST)) $smarty->assign("ok_message", 'Добавлена новая должность для члена команды');
    else $smarty->assign("error_message", 'Не добавлена новая должность для члена команды');
}

// Редактирование члена команды
if (!empty($_POST['edit_team_staff'])) {
    if ($team->updateConnectStT($_POST)) $smarty->assign("ok_message", 'Обновлен член команды');
    else $smarty->assign("error_message", 'Не обновлен член команды');
}

// Удаление члена команды (полностью, без сохранения в историю)
if (!empty($_POST['delete_team_staff'])) {
    if ($team->deleteConnectStT($_POST)) $smarty->assign("ok_message", 'Удалена запись о члене команды');
    else $smarty->assign("error_message", 'Не удалена запись о члене команды');
}

if (!empty($_GET['staff'])) {
    $t_staff_item = $team->getTStaffItem($_GET['staff']);
    $smarty->assign("t_staff_item", $t_staff_item);
}
$app_type = 'player';
if (!empty($_GET['app_type']) && ($_GET['app_type'] == 'player' || $_GET['app_type'] == '')) $app_type = 'player';
if (!empty($_GET['app_type']) && $_GET['app_type'] == 'head') $app_type = 'head';
if (!empty($_GET['app_type']) && $_GET['app_type'] == 'rest') $app_type = 'rest';
$smarty->assign("app_type", $app_type);

$get_item = (!empty($_GET['item'])) ? $_GET['item'] : 0;
// ОПТИМИЗАЦИЯ: Не загружаем весь список из 4440+ игроков, используется AJAX autocomplete
// $smarty->assign("staff_list", $team->getTStaffList());

// ОПТИМИЗАЦИЯ: Загружаем списки состава только когда НЕ редактируем конкретного игрока
// Это предотвращает лишние запросы при открытии формы редактирования/добавления/удаления
// Для быстрого переключения между вкладками Игроки/Персонал загружаем данные для ОБОИХ типов сразу
if (empty($_GET['staff'])) {
    // Загружаем данные для игроков
    $smarty->assign("team_staff_player_byapp", $team->getConnectStTByapp($get_item, 'player'));
    $smarty->assign("team_staff_player_byname", $team->getConnectStTByname($get_item, 'player'));

    // Загружаем данные для персонала
    $smarty->assign("team_staff_rest_byapp", $team->getConnectStTByapp($get_item, 'rest'));
    $smarty->assign("team_staff_rest_byname", $team->getConnectStTByname($get_item, 'rest'));
}

// СОСТАВ КОМАНДЫ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// СТАДИОНЫ ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////
if (!empty($_POST['add_new_stadium'])) {
    if ($team->createStadium($_POST)) $smarty->assign("ok_message", 'Стадион добавлен');
    else $smarty->assign("error_message", 'Стадион не добавлен');
}

if (!empty($_POST['save_stadium_changes'])) {
    if ($team->updateStadium($_POST)) $smarty->assign("ok_message", 'Стадион обновлен');
    else $smarty->assign("error_message", 'Стадион не обновлен');
}

if (!empty($_POST['delete_stadium'])) {
    if ($team->deleteStadium($_POST['ct_id'])) header('Location: ./?show=team&get=stadium');
    else $smarty->assign("error_message", 'Стадион не удален');
}

if (!empty($_GET['get']) && $_GET['get'] == 'stadiumedit' && !empty($_GET['item'])) {
    $stadium_item = $team->getStadiumItem($_GET['item']);
    $smarty->assign("stadium_item", $stadium_item);
}

$smarty->assign("team_stadium_list_id", $team->getTeamStadiumListID());
$smarty->assign("team_stadium_list", $team->getTeamStadiumList());

// СТАДИОНЫ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// ГОРОДА ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////
if (!empty($_POST['add_new_city'])) {
    if ($team->createCity($_POST)) $smarty->assign("ok_message", 'Город добавлен');
    else $smarty->assign("error_message", 'Город не добавлен');
}

if (!empty($_POST['save_city_changes'])) {
    if ($team->updateCity($_POST)) $smarty->assign("ok_message", 'Город обновлен');
    else $smarty->assign("error_message", 'Город не обновлен');
}

if (!empty($_POST['delete_city'])) {
    if ($team->deleteCity($_POST['ct_id'])) header('Location: ./?show=team&get=city');
    else $smarty->assign("error_message", 'Город не удален');
}

if (!empty($_GET['get']) && $_GET['get'] == 'cityedit' && !empty($_GET['item'])) {
    $city_item = $team->getCityItem($_GET['item']);
    $smarty->assign("city_item", $city_item);
}

$smarty->assign("team_city_list_id", $team->getTeamCityListID());
$smarty->assign("team_city_list", $team->getTeamCityList());

// ГОРОДА ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// СТРАНЫ ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////
if (!empty($_POST['add_new_country'])) {
    if ($team->createCountry($_POST)) $smarty->assign("ok_message", 'Страна добавлена');
    else $smarty->assign("error_message", 'Страна не добавлена');
}

if (!empty($_POST['save_country_changes'])) {
    if ($team->updateCountry($_POST)) $smarty->assign("ok_message", 'Страна обновлена');
    else $smarty->assign("error_message", 'Страна не обновлена');
}

if (!empty($_POST['delete_country'])) {
    if ($team->deleteCountry($_POST['cn_id'])) header('Location: ./?show=team&get=country');
    else $smarty->assign("error_message", 'Страна не удалена');
}

if (!empty($_GET['get']) && $_GET['get'] == 'country ┃ edit' && !empty($_GET['item'])) {
    $country_item = $team->getCountryItem($_GET['item']);
    $smarty->assign("country_item", $country_item);
}

$smarty->assign("team_country_list_id", $team->getTeamCountryListID());
$smarty->assign("team_country_list", $team->getTeamCountryList());

// СТРАНЫ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// ДОЛЖНОСТИ ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////
if (!empty($_POST['add_new_category'])) {
    if ($team->createCategory($_POST)) $smarty->assign("ok_message", 'Должность добавлена');
    else $smarty->assign("error_message", 'Должность не добавлена');
}

if (!empty($_POST['save_category_changes'])) {
    if ($team->updateCategory($_POST)) $smarty->assign("ok_message", 'Должность обновлена');
    else $smarty->assign("error_message", 'Должность не обновлена');
}

if (!empty($_POST['delete_category'])) {
    if ($team->deleteCategory($_POST['app_id'])) header('Location: ./?show=team&get=categories');
    else $smarty->assign("error_message", 'Должность не удалена');
}

if (!empty($_GET['get']) && $_GET['get'] == 'categedit' && !empty($_GET['item'])) {
    $category_item = $team->getCategoryItem($_GET['item']);
    $smarty->assign("category_item", $category_item);
}

// ОПТИМИЗАЦИЯ: Загружаем категории должностей только когда нужны
// 1. При редактировании команды - нужны для вкладки "Состав"
// 2. На вкладке "Должности" (get=categories или get=categedit)
$needs_categories = false;

// Для редактирования команды - категории нужны для выбора должности при добавлении/редактировании состава
if (!empty($_GET['get']) && $_GET['get'] == 'edit' && !empty($_GET['item'])) {
    $needs_categories = true;
}

// Для вкладки "Должности" (управление должностями)
if (!empty($_GET['get']) && ($_GET['get'] == 'categories' || $_GET['get'] == 'categedit')) {
    $needs_categories = true;
}

if ($needs_categories) {
    $smarty->assign("team_categories_list_id", $team->getTeamCategoriesListID());
    $smarty->assign("team_categories_list", $team->getTeamCategoriesList());
}

// ДОЛЖНОСТИ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// НАСТРОЙКИ ///////// НАЧАЛО /////////////////////////////////////////////////////////////////
if (!empty($_POST['save_team_left_active_settings'])) {
    if ($_POST['is_active'] == true) $is_active = 1;
    else $is_active = 0;
    $elems = array(
        "set_value" => $is_active,
        "set_datetime_edit" => date('Y-m-d H:i:s'), // Текущее время в формате строки для MySQL 8.x
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "is_active_team_left"
    );
    if ($team->saveSettings($elems, $condition)) {
        $smarty->assign("ok_message", 'Настройки обновлены');
    } else {
        $smarty->assign("error_message", 'Настройки не обновлены');
        error_log("[ERROR] Не удалось сохранить настройки is_active_team_left", 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
    }
}

if (!empty($_POST['save_team_left_count_settings'])) {
    $_POST['cnv_value'] = intval($_POST['cnv_value']);
    $elems = array(
        "set_value" => $_POST['cnv_value'],
        "set_datetime_edit" => date('Y-m-d H:i:s'), // Текущее время в формате строки для MySQL 8.x
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "count_team_left"
    );
    if ($team->saveSettings($elems, $condition)) {
        $smarty->assign("ok_message", 'Настройки обновлены');
    } else {
        $smarty->assign("error_message", 'Настройки не обновлены');
        error_log("[ERROR] Не удалось сохранить настройки count_team_left", 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
    }
}

if (!empty($_POST['save_team_count_settings'])) {
    $_POST['cnv_value'] = intval($_POST['cnv_value']);
    $elems = array(
        "set_value" => $_POST['cnv_value'],
        "set_datetime_edit" => date('Y-m-d H:i:s'), // Текущее время в формате строки для MySQL 8.x
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "count_team_page"
    );
    if ($team->saveSettings($elems, $condition)) {
        $smarty->assign("ok_message", 'Настройки обновлены');
    } else {
        $smarty->assign("error_message", 'Настройки не обновлены');
        error_log("[ERROR] Не удалось сохранить настройки count_team_page", 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
    }
}

if (!empty($_GET['get']) && $_GET['get'] == 'settings') {
    $team_settings = $team->getTeamSettings();
    if ($team_settings) {
        $smarty->assign("team_settings_list", $team_settings);
    } else {
        $smarty->assign("error_message", 'Не удалось загрузить настройки команды');
        error_log("[ERROR] Не удалось загрузить настройки команды в getTeamSettings()", 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
    }
}

// НАСТРОЙКИ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// ФОТО И ВИДЕО ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////

// Photos for team
if (!empty($_POST['add_new_team_photo']) && !empty($_GET['item'])) {
    $team_item = $team->getTeamItem($_GET['item']);
    if ($team->savePhoto($team_item, $photos)) {
        $smarty->assign("ok_message", 'Фотография добавлена');
        // Reload photo list to show newly added photo
        $team_photo_list = $photos->getTypePhotoList($_GET['item'], 'team');
        $smarty->assign("team_photo_list", $team_photo_list);

        // Загрузка списков для dropdown после операции
        $gallery_list = $photos->getPhotoGalleryList();
        $smarty->assign("gallery_list", $gallery_list);
        $team_photo_gallery = $photos->getTypePhotoGallery($_GET['item'], 'team');
        $smarty->assign("team_photo_gallery", $team_photo_gallery);
    } else {
        $smarty->assign("error_message", 'Фотография не добавлена');
        error_log("[ERROR] Не удалось добавить фото для команды ID: " . ($_GET['item'] ?? 'не указано'), 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
    }
}

if (!empty($_POST['save_edited_team_photo'])) {
    if (!empty($_POST['ph_type_main'])) $photos->resetTypeMainPhotos($_POST['n_id'], 'team');
    $photos->updatePhotoGalleryCategory($_POST['ph_gallery_id'], !empty($_POST['phg_phc_id']) ? $_POST['phg_phc_id'] : 0);
    if ($photos->saveEditedPhoto($_FILES['file_photo'], $_POST)) {
        $smarty->assign("ok_message", 'Фотография обновлена');
        // Reload photo list to show updated photo
        if (!empty($_GET['item'])) {
            $team_photo_list = $photos->getTypePhotoList($_GET['item'], 'team');
            $smarty->assign("team_photo_list", $team_photo_list);

            // Загрузка списков для dropdown после операции
            $gallery_list = $photos->getPhotoGalleryList();
            $smarty->assign("gallery_list", $gallery_list);
            $team_photo_gallery = $photos->getTypePhotoGallery($_GET['item'], 'team');
            $smarty->assign("team_photo_gallery", $team_photo_gallery);
        }
    } else {
        $smarty->assign("error_message", 'Фотография не обновлена');
    }
}

if (!empty($_POST['delete_edited_team_photo'])) {
    $_GET['item'] = intval($_GET['item']);
    if ($photos->deletePhoto($_POST['ph_id'])) header('Location: ./?show=team&get=edit&item='.$_GET['item'].'&cont=4');
    else $smarty->assign("error_message", 'Фотография не удалена');
}

if (!empty($_GET['g_f_item'])) {
    $smarty->assign("photo_item", $photos->getPhotoItem($_GET['g_f_item']));
    // Загрузка списков для формы редактирования фото
    $gallery_list = $photos->getPhotoGalleryList();
    $smarty->assign("gallery_list", $gallery_list);
    $team_photo_gallery = $photos->getTypePhotoGallery($_GET['item'], 'team');
    $smarty->assign("team_photo_gallery", $team_photo_gallery);
}

// Videos for team
if (!empty($_POST['add_new_team_video']) && !empty($_GET['item'])) {
    $team_item = $team->getTeamItem($_GET['item']);
    if ($team->saveVideo($team_item, $videos)) {
        $smarty->assign("ok_message", 'Видео добавлено');

        // Загрузка списков для dropdown после операции
        $team_video_list = $videos->getTypeVideoList($_GET['item'], 'team');
        $smarty->assign("team_video_list", $team_video_list);
        $gallery_list_video = $videos->getVideoGalleryList();
        $smarty->assign("gallery_list_video", $gallery_list_video);
        $video_category_list = $videos->getVideoCategoryList();
        $smarty->assign("video_category_list", $video_category_list);
    } else {
        $smarty->assign("error_message", 'Видео не добавлено');
        error_log("[ERROR] Не удалось добавить видео для команды ID: " . ($_GET['item'] ?? 'не указано'), 3, $_SERVER['DOCUMENT_ROOT'] . "/admin_team_debug.log");
    }
}

if (!empty($_POST['save_edited_team_video'])) {
    $videos->updateVideoGalleryCategory($_POST['v_gallery_id'], !empty($_POST['vg_vc_id']) ? $_POST['vg_vc_id'] : 0);
    if ($videos->saveEditedVideo($_POST)) {
        $smarty->assign("ok_message", 'Видео обновлено');

        // Загрузка списков для dropdown после операции
        $team_video_list = $videos->getTypeVideoList($_GET['item'], 'team');
        $smarty->assign("team_video_list", $team_video_list);
        $gallery_list_video = $videos->getVideoGalleryList();
        $smarty->assign("gallery_list_video", $gallery_list_video);
        $video_category_list = $videos->getVideoCategoryList();
        $smarty->assign("video_category_list", $video_category_list);
    } else {
        $smarty->assign("error_message", 'Видео не обновлено');
    }
}

if (!empty($_POST['save_edited_team_preview'])) {
    if ($videos->saveEditedVideoPreview($_FILES['file_photo_preview'], $_POST['v_id'])) {
        $smarty->assign("ok_message", 'Превью для видео обновлено');

        // Загрузка списков для dropdown после операции
        $team_video_list = $videos->getTypeVideoList($_GET['item'], 'team');
        $smarty->assign("team_video_list", $team_video_list);
    } else {
        $smarty->assign("error_message", 'Превью для видео не обновлено');
    }
}

if (!empty($_POST['delete_edited_team_video'])) {
    if ($videos->deleteVideo($_POST['v_id'])) header('Location: ./?show=team&get=edit&item='.$_GET['item'].'&cont=4');
    else $smarty->assign("error_message", 'Видео не удалено');
}

if (!empty($_GET['g_v_item'])) {
    $smarty->assign("video_item", $videos->getVideoItem($_GET['g_v_item']));

    // Загрузка списков для формы редактирования видео
    $gallery_list_video = $videos->getVideoGalleryList();
    $smarty->assign("gallery_list_video", $gallery_list_video);
    $video_category_list = $videos->getVideoCategoryList();
    $smarty->assign("video_category_list", $video_category_list);
}

// ФОТО И ВИДЕО ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////
?>