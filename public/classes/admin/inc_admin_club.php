<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('../classes/admin/admin_club.php');
include_once('../classes/admin/admin_photos.php');
include_once('../classes/admin/admin_videos.php');

$club = new club;
$photos = new photos;
$videos = new videos;

// КЛУБЫ ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////
if($_POST['add_new_club']){
    if ($club->createClub($_POST)) $smarty->assign("ok_message", 'Клуб добавлен');
    else $smarty->assign("error_message", 'Клуб не добавлен');
}

if($_POST['save_club_changes']){
    if ($club->updateClub($_POST)) $smarty->assign("ok_message", 'Клуб обновлен');
    else $smarty->assign("error_message", 'Клуб не обновлен');
}

if($_POST['delete_club']){
    if ($club->deleteClub($_POST['cl_id'])) header('Location: ./?show=club');
    else $smarty->assign("error_message", 'Клуб не удален');
}

if ($_GET['get']=='edit' and $_GET['item']>0){
    $club_item = $club->getClubItem($_GET['item']);
    $smarty->assign("club_item", $club_item);
}

$smarty->assign("club_list", $club->getClubList());

// КЛУБЫ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// СОСТАВ КЛУБА КОМАНДЫ ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////

if($_POST['add_club_team']){
    if ($club->createConnectTCl($_POST)) $smarty->assign("ok_message", 'Добавлена новая команда в клуб');
    else $smarty->assign("error_message", 'Клуб не пополнен');
}

if($_POST['edit_club_team']){
    if ($club->updateConnectTCl($_POST)) $smarty->assign("ok_message", 'Обновлена команда клуба');
    else $smarty->assign("error_message", 'Не бновлена команда клуба');
}

if($_POST['quit_club_team']){
    if ($club->quitConnectTCl($_POST)) $smarty->assign("ok_message", 'Уволена команда клуба');
    else $smarty->assign("error_message", 'Не уволена команда клуба');
}

if($_POST['delete_club_team']){
    if ($club->deleteConnectTCl($_POST)) $smarty->assign("ok_message", 'Удалена запись о команде клуба');
    else $smarty->assign("error_message", 'Не удалена запись о команде клуба');
}

if ($_GET['team']>0){
    $cl_team_item = $club->getClTItem($_GET['team']);
    $smarty->assign("cl_team_item", $cl_team_item);
}

$smarty->assign("club_team_list", $club->getClubTeamList($_GET['item']));
$smarty->assign("club_team_history", $club->getHistoryConnectTCl($_GET['item']));

// СОСТАВ КЛУБА КОМАНДЫ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// СОСТАВ КЛУБА ЛЮДИ ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////

if($_POST['return_club_staff']){
    if ($club->returnConnectStCl($_POST)) $smarty->assign("ok_message", 'Возвращен член клуба');
    else $smarty->assign("error_message", 'Не удалось вернуть члена клуба');
}

if($_POST['add_club_staff']){
    if ($club->createConnectStCl($_POST)) $smarty->assign("ok_message", 'Добавлен новый член клуба');
    else $smarty->assign("error_message", 'Клуб не пополнен');
}

if($_POST['add_new_club_staff']){ // добавить новую должность для человека
    if ($club->createNewConnectStCl($_POST)) $smarty->assign("ok_message", 'Добавлена новая должность для члена клуба');
    else $smarty->assign("error_message", 'Не добавлена новая должность для члена клуба');
}

if($_POST['edit_club_staff']){
    if ($club->updateConnectStCl($_POST)) $smarty->assign("ok_message", 'Обновлен член клуба');
    else $smarty->assign("error_message", 'Не бновлен член клуба');
}

if($_POST['quit_club_staff']){
    if ($club->quitConnectStCl($_POST)) $smarty->assign("ok_message", 'Уволен член клуба');
    else $smarty->assign("error_message", 'Не уволен член клуба');
}

if($_POST['delete_club_staff']){
    if ($club->deleteConnectStCl($_POST)) $smarty->assign("ok_message", 'Удалена запись о члене клуба');
    else $smarty->assign("error_message", 'Не удалена запись о члене клуба');
}

if ($_GET['staff']>0){
    $cl_staff_item = $club->getClStaffItem($_GET['staff']);
    $smarty->assign("cl_staff_item", $cl_staff_item);
}

if ($_GET['app_type'] == 'player' or $_GET['app_type'] == '') $app_type = 'player';
if ($_GET['app_type'] == 'head') $app_type = 'head';
if ($_GET['app_type'] == 'rest') $app_type = 'rest';
$smarty->assign("app_type", $app_type);

$smarty->assign("staff_list", $club->getClStaffList());
//$smarty->assign("club_staff_list", $club->getConnectStCl($_GET['item']));
$smarty->assign("club_staff_history", $club->getHistoryConnectStCl($_GET['item'])); // история

$smarty->assign("club_staff_list_byapp", $club->getConnectStClByapp($_GET['item'], $app_type)); // с сортировкой по должности
$smarty->assign("club_staff_list_byname", $club->getConnectStClByname($_GET['item'], $app_type)); // с сортировкой по имени

// СОСТАВ КЛУБА ЛЮДИ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// СТАДИОНЫ ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////
if($_POST['add_new_stadium']){
    if ($club->createStadium($_POST)) $smarty->assign("ok_message", 'Стадион добавлен');
    else $smarty->assign("error_message", 'Стадион не добавлен');
}

if($_POST['save_stadium_changes']){
    if ($club->updateStadium($_POST)) $smarty->assign("ok_message", 'Стадион обновлен');
    else $smarty->assign("error_message", 'Стадион не обновлена');
}

if($_POST['delete_stadium']){
    if ($club->deleteStadium($_POST['ct_id'])) header('Location: ./?show=club&get=stadium');
    else $smarty->assign("error_message", 'Стадион не удален');
}

if ($_GET['get']=='stadiumedit' and $_GET['item']>0){
    $stadium_item = $club->getStadiumItem($_GET['item']);
    $smarty->assign("stadium_item", $stadium_item);
}

$smarty->assign("club_stadium_list_id", $club->getClubStadiumListID());
$smarty->assign("club_stadium_list", $club->getClubStadiumList());

// СТАДИОНЫ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// ГОРОДА ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////
if($_POST['add_new_city']){
    if ($club->createCity($_POST)) $smarty->assign("ok_message", 'Город добавлен');
    else $smarty->assign("error_message", 'Город не добавлен');
}

if($_POST['save_city_changes']){
    if ($club->updateCity($_POST)) $smarty->assign("ok_message", 'Город обновлен');
    else $smarty->assign("error_message", 'Город не обновлена');
}

if($_POST['delete_city']){
    if ($club->deleteCity($_POST['ct_id'])) header('Location: ./?show=club&get=city');
    else $smarty->assign("error_message", 'Город не удален');
}

if ($_GET['get']=='cityedit' and $_GET['item']>0){
    $city_item = $club->getCityItem($_GET['item']);
    $smarty->assign("city_item", $city_item);
}

$smarty->assign("club_city_list_id", $club->getClubCityListID());
$smarty->assign("club_city_list", $club->getClubCityList());

// ГОРОДА ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// СТРАНЫ ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////
if($_POST['add_new_country']){
    if ($club->createCountry($_POST)) $smarty->assign("ok_message", 'Страна добавлена');
    else $smarty->assign("error_message", 'Страна не добавлена');
}

if($_POST['save_country_changes']){
    if ($club->updateCountry($_POST)) $smarty->assign("ok_message", 'Страна обновлена');
    else $smarty->assign("error_message", 'Страна не обновлена');
}

if($_POST['delete_country']){
    if ($club->deleteCountry($_POST['cn_id'])) header('Location: ./?show=club&get=country');
    else $smarty->assign("error_message", 'Страна не удалена');
}

if ($_GET['get']=='countryedit' and $_GET['item']>0){
    $country_item = $club->getCountryItem($_GET['item']);
    $smarty->assign("country_item", $country_item);
}

$smarty->assign("club_country_list_id", $club->getClubCountryListID());
$smarty->assign("club_country_list", $club->getClubCountryList());

// СТРАНЫ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// ДОЛЖНОСТИ ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////
if($_POST['add_new_category']){
    if ($club->createCategory($_POST)) $smarty->assign("ok_message", 'Должность добавлена');
    else $smarty->assign("error_message", 'Должность не добавлена');
}

if($_POST['save_category_changes']){
    if ($club->updateCategory($_POST)) $smarty->assign("ok_message", 'Должность обновлена');
    else $smarty->assign("error_message", 'Должность не обновлена');
}

if($_POST['delete_category']){
    if ($club->deleteCategory($_POST['app_id'])) header('Location: ./?show=club&get=categories');
    else $smarty->assign("error_message", 'Должность не удалена');
}

if ($_GET['get']=='categedit' and $_GET['item']>0){
    $category_item = $club->getCategoryItem($_GET['item']);
    $smarty->assign("category_item", $category_item);
}

$smarty->assign("club_categories_list_id", $club->getClubCategoriesListID());
$smarty->assign("club_categories_list", $club->getClubCategoriesList());

// ДОЛЖНОСТИ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// НАСТРОЙКИ ///////// НАЧАЛО /////////////////////////////////////////////////////////////////
if($_POST['save_club_left_active_settings']){
    if($_POST['is_active']==true) $is_active =1;
    else $is_active = 0;
    $elems = array(
        "set_value" => $is_active,
        "set_datetime_edit" => 'NOW()',
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "is_active_club_left"
    );
    if ($club->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if($_POST['save_club_left_count_settings']){
    $_POST['cnv_value'] = intval($_POST['cnv_value']);
    $elems = array(
        "set_value" => $_POST['cnv_value'],
        "set_datetime_edit" => 'NOW()',
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "count_club_left"
    );
    if ($club->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if($_POST['save_club_count_settings']){
    $_POST['cnv_value'] = intval($_POST['cnv_value']);
    $elems = array(
        "set_value" => $_POST['cnv_value'],
        "set_datetime_edit" => 'NOW()',
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "count_club_page"
    );
    if ($club->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if ($_GET['get'] == 'settings') {
    $smarty->assign("club_settings_list", $club->getClubSettings());

    // Заголовки ////////////////////////////////////////////////////////////////
    include_once('../classes/admin/admin_conf_vars.php');
    $conf = new conf_vars;

    if ($_POST['save_var']){
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
$club_id = intval($_GET['item']);
$type = 'club';

if ($_GET['cont'] == 4){
    // Photos for club
    if($_POST['add_new_club_photo']){
        if ($club->savePhoto($club_item, $photos)) $smarty->assign("ok_message", 'Фотография добавлена');
        else $smarty->assign("error_message", 'Фотография не добавлена');
    }

    if($_POST['save_edited_club_photo']){
        if ($_POST['ph_type_main']) $photos->resetTypeMainPhotos($_POST['cl_id'], $type);
        if ($photos->saveEditedPhoto($_FILES['file_photo'], $_POST)) $smarty->assign("ok_message", 'Фотография обновлена');
        else $smarty->assign("error_message", 'Фотография не обновлена');
    }

    if($_POST['delete_edited_club_photo']){
        $_GET['item'] = intval($_GET['item']);
        if ($photos->deletePhoto($_POST['ph_id'])) header('Location: ./?show=club&get=edit&item='.$_GET['item'].'&cont=4');
        else $smarty->assign("error_message", 'Фотография не удалена');
    }

    if ($_GET['g_f_item']>0){
        $smarty->assign("photo_item", $photos->getPhotoItem($_GET['g_f_item']));
    }

    // Videos for club
    if($_POST['add_new_club_video']){
        if ($club->saveVideo($club_item, $videos)) $smarty->assign("ok_message", 'Видео добавлено');
        else $smarty->assign("error_message", 'Видео не добавлено');
    }

    if($_POST['save_edited_club_video']){
        if ($videos->saveEditedVideo($_POST)) $smarty->assign("ok_message", 'Видео обновлено');
        else $smarty->assign("error_message", 'Видео не обновлено');
    }

    if($_POST['save_edited_club_preview']){
        if ($videos->saveEditedVideoPreview($_FILES['file_photo_preview'], $_POST['v_id'])) $smarty->assign("ok_message", 'Превью для видео обновлено');
        else $smarty->assign("error_message", 'Превью для видео не обновлено');
    }

    if($_POST['delete_edited_club_video']){
        if ($videos->deleteVideo($_POST['v_id'])) header('Location: ./?show=club&get=edit&item='.$_GET['item'].'&cont=4');
        else $smarty->assign("error_message", 'Видео не удалено');
    }

    if ($_GET['g_v_item']>0){
        $smarty->assign("video_item", $videos->getVideoItem($_GET['g_v_item']));
    }
    $smarty->assign("club_photo_gallery", $photos->getTypePhotoGallery($club_id, $type));
    $smarty->assign("gallery_list", $photos->getPhotoGalleryList());
    $smarty->assign("club_video_gallery", $videos->getTypeVideoGallery($club_id, $type));
    $smarty->assign("gallery_list_video", $videos->getVideoGalleryList());
}

$smarty->assign("club_photo_list", $photos->getTypePhotoList($club_id, $type));
$smarty->assign("club_video_list", $videos->getTypeVideoList($club_id, $type));

// ГАЛЕРЕЯ ////////// КОНЕЦ //////////////////////////////////////////////////////////////////
?>