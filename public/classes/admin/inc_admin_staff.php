<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('../classes/admin/admin_staff.php');
include_once('../classes/admin/admin_photos.php');
include_once('../classes/admin/admin_videos.php');

$staff = new staff;
$photos = new photos;
$videos = new videos;

// ЛЮДИ ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////
if(!empty($_POST['add_new_staff'])){
    if ($staff->createStaff($_POST)) $smarty->assign("ok_message", 'Информация о человеке добавлена');
    else $smarty->assign("error_message", 'Информация о человеке не добавлена');
}

if(!empty($_POST['save_staff_changes'])){
    if ($staff->updateStaff($_POST)) $smarty->assign("ok_message", 'Информация о человеке обновлена');
    else $smarty->assign("error_message", 'Информация о человеке не обновлена');
}

if(!empty($_POST['delete_staff'])){
    if ($staff->deleteStaff($_POST['st_id'])) header('Location: ./?show=staff');
    else $smarty->assign("error_message", 'Информация о человеке не удалена');
}

// Объединение игроков (merge)
if(!empty($_POST['merge_staff'])){
    $target_id = intval($_POST['st_id']);
    $source_id = intval($_POST['merge_source_id']);
    $result = $staff->mergeStaff($target_id, $source_id);
    if ($result['success']) {
        $smarty->assign("ok_message", 'Игроки объединены: ' . $result['source'] . ' → ' . $result['target'] . '<br>' . implode('<br>', $result['merged']));
    } else {
        $smarty->assign("error_message", 'Ошибка объединения: ' . $result['error']);
    }
}

if(!empty($_POST['save_staff_custom_statistics'])){
    if ($staff->createStaffCustomStatistics($_POST)) $smarty->assign("ok_message", 'Статистика добавлена');
    else $smarty->assign("error_message", 'Статистика не добавлена');
}
if(!empty($_POST['update_staff_custom_statistics'])){
    if ($staff->updateStaffCustomStatistics($_POST)) $smarty->assign("ok_message", 'Статистика обновлена');
    else $smarty->assign("error_message", 'Статистика не обновлена');
}

if (!empty($_GET['get']) && $_GET['get']=='edit' && !empty($_GET['item'])){
    $staff_item = $staff->getStaffItem($_GET['item']);
    $smarty->assign("staff_item", $staff_item);

    // meta SEO functions start
    $meta_seo_item = $staff_item['st_id'];
    $meta_seo_item_type = 'staff';
    include_once('../classes/admin/inc_admin_meta_seo.php');
    // meta SEO functions finish
    include_once('../classes/admin/admin_team.php');
    $team = new team;
    $smarty->assign("team_list", $team->getTeamList());
}

$_GET['sort'] = (!empty($_GET['sort']))?intval($_GET['sort']):0;

$_GET['letter'] = (!empty($_GET['letter'])) ? rawurldecode($_GET['letter']) : '';
$_GET['search'] = (!empty($_GET['search']))?trim($_GET['search']):'';
$smarty->assign("sort", $_GET['sort']);
$smarty->assign("letter", $_GET['letter']);
$smarty->assign("search", $_GET['search']);
$smarty->assign("letter_list", $staff->getLetterList($_GET['sort']));
$smarty->assign("staff_list", $staff->getStaffList($_GET['sort'], $_GET['letter'], $_GET['search']));

// ЛЮДИ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// НАСТРОЙКИ ///////// НАЧАЛО /////////////////////////////////////////////////////////////////

if(!empty($_POST['save_staff_left_active_settings'])){
    if($_POST['is_active']==true) $is_active =1;
    else $is_active = 0;
    $elems = array(
        "set_value" => $is_active,
        "set_datetime_edit" => 'NOW()',
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "is_active_staff_left"
    );
    if ($staff->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if(!empty($_POST['save_staff_left_count_settings'])){
    $_POST['cnv_value'] = intval($_POST['cnv_value']);
    $elems = array(
        "set_value" => $_POST['cnv_value'],
        "set_datetime_edit" => 'NOW()',
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "count_staff_left"
    );
    if ($staff->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if(!empty($_POST['save_staff_count_settings'])){
    $_POST['cnv_value'] = intval($_POST['cnv_value']);
    $elems = array(
        "set_value" => $_POST['cnv_value'],
        "set_datetime_edit" => 'NOW()',
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "count_staff_page"
    );
    if ($staff->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if (!empty($_GET['get']) && $_GET['get'] == 'settings') {
    $smarty->assign("staff_settings_list", $staff->getStaffSettings());

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
$staff_id = (!empty($_GET['item']))?intval($_GET['item']):0;
$type = 'staff';

if (!empty($_GET['cont']) && $_GET['cont'] == 4){
    // Photos for staff
    if($_POST['add_new_staff_photo']){
        if ($staff->savePhoto($staff_item, $photos)) $smarty->assign("ok_message", 'Фотография добавлена');
        else $smarty->assign("error_message", 'Фотография не добавлена');
    }

    if($_POST['save_edited_staff_photo']){
        if ($_POST['ph_type_main']) $photos->resetTypeMainPhotos($_POST['st_id'], $type);
        $photos->updatePhotoGalleryCategory($_POST['ph_gallery_id'], $_POST['phg_phc_id']);
        if ($photos->saveEditedPhoto($_FILES['file_photo'], $_POST)) $smarty->assign("ok_message", 'Фотография обновлена');
        else $smarty->assign("error_message", 'Фотография не обновлена');
    }

    if($_POST['delete_edited_staff_photo']){
        $_GET['item'] = intval($_GET['item']);
        if ($photos->deletePhoto($_POST['ph_id'])) header('Location: ./?show=staff&get=edit&item='.$_GET['item'].'&cont=4');
        else $smarty->assign("error_message", 'Фотография не удалена');
    }

    if ($_GET['g_f_item']>0){
        $smarty->assign("photo_item", $photos->getPhotoItem($_GET['g_f_item']));
    }

    // Videos for staff
    if($_POST['add_new_staff_video']){
        if ($staff->saveVideo($staff_item, $videos)) $smarty->assign("ok_message", 'Видео добавлено');
        else $smarty->assign("error_message", 'Видео не добавлено');
    }

    if($_POST['save_edited_staff_video']){
        $videos->updateVideoGalleryCategory($_POST['v_gallery_id'], $_POST['vg_vc_id']);
        if ($videos->saveEditedVideo($_POST)) $smarty->assign("ok_message", 'Видео обновлено');
        else $smarty->assign("error_message", 'Видео не обновлено');
    }

    if($_POST['save_edited_staff_preview']){
        if ($videos->saveEditedVideoPreview($_FILES['file_photo_preview'], $_POST['v_id'])) $smarty->assign("ok_message", 'Превью для видео обновлено');
        else $smarty->assign("error_message", 'Превью для видео не обновлено');
    }

    if($_POST['delete_edited_staff_video']){
        if ($videos->deleteVideo($_POST['v_id'])) header('Location: ./?show=staff&get=edit&item='.$_GET['item'].'&cont=4');
        else $smarty->assign("error_message", 'Видео не удалено');
    }

    if ($_GET['g_v_item']>0){
        $smarty->assign("video_item", $videos->getVideoItem($_GET['g_v_item']));
    }
    $smarty->assign("staff_photo_gallery", $photos->getTypePhotoGallery($staff_id, $type));
    $smarty->assign("gallery_list", $photos->getPhotoGalleryList());
    $smarty->assign("photo_category_list", $photos->getPhotoCategoryList());
    $smarty->assign("staff_video_gallery", $videos->getTypeVideoGallery($staff_id, $type));
    $smarty->assign("gallery_list_video", $videos->getVideoGalleryList());
    $smarty->assign("video_category_list", $videos->getVideoCategoryList());

}

$smarty->assign("staff_photo_list", $photos->getTypePhotoList($staff_id, $type));
$smarty->assign("staff_video_list", $videos->getTypeVideoList($staff_id, $type));

// ГАЛЕРЕЯ ////////// КОНЕЦ //////////////////////////////////////////////////////////////////
