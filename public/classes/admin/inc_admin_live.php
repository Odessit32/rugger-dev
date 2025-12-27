<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('../classes/admin/admin_live.php');
include_once('../classes/admin/admin_photos.php');
include_once('../classes/admin/admin_videos.php');

$live = new live;
$photos = new photos;
$videos = new videos;

// LIVE ///////// BEGIN ///////////////////////////////////////////////////////////////////
if(!empty($_POST['add_new_live'])){
    if ($live->createLive($_POST)) $smarty->assign("ok_message", 'Новость добавлена');
    else $smarty->assign("error_message", 'Новость не добавлена');
}

if(!empty($_POST['save_live_changes'])){
    if ($live->updateLive($_POST)) $smarty->assign("ok_message", 'Новость обновлена');
    else $smarty->assign("error_message", 'Новость не обновлена');
}

if(!empty($_POST['delete_live'])){
    if ($live->deleteLive($_POST['n_id'])) header('Location: ./?show=live'.(($_GET['page']>0)?'&page='.$_GET['page']:'').(($_GET['nnc']>0)?'&nnc='.$_GET['nnc']:''));
    else $smarty->assign("error_message", 'Новость не удалена');
}

if (!empty($_GET['get']) && !empty($_GET['item']) && $_GET['get']=='edit' && $_GET['item']>0){
    $live_item = $live->getLiveItem($_GET['item']);
    $smarty->assign("live_item", $live_item);

    // meta SEO functions start
    $meta_seo_item = (!empty($live_item['n_id']))?$live_item['n_id']:0;
    $meta_seo_item_type = 'live';
    include_once('../classes/admin/inc_admin_meta_seo.php');
    // meta SEO functions finish
}
$smarty->assign("country_list", $live->getCountryList());
$smarty->assign("championship_list", $live->getChampionshipList());
$smarty->assign("champ_list", $live->getChampList());
$page = (!empty($_GET['page'])) ? $_GET['page'] : 0;
$nnc = (!empty($_GET['nnc'])) ? $_GET['nnc'] : 0;
$smarty->assign("live_list", $live->getLiveList(intval($page), 20, $nnc));
$smarty->assign("live_pages", $live->getLivePages(intval($page), 20, $nnc));
$smarty->assign("authors", $live->getAuthorsList());

// LIVE ///////// FINISH  ///////////////////////////////////////////////////////////////////

// RUBRICS ///////// BEGIN ///////////////////////////////////////////////////////////////////
if(!empty($_POST['add_new_category'])){
    if ($live->createCategory($_POST)) $smarty->assign("ok_message", 'Рубрика добавлена');
    else $smarty->assign("error_message", 'Рубрика не добавлена');
}

if(!empty($_POST['save_category_changes'])){
    if ($live->updateCategory($_POST)) $smarty->assign("ok_message", 'Рубрика обновлена');
    else $smarty->assign("error_message", 'Рубрика не обновлена');
}

if(!empty($_POST['delete_category'])){
    if ($live->deleteCategory($_POST['nс_id'])) header('Location: ./?show=live&get=categories');
    else $smarty->assign("error_message", 'Рубрика не удалена');
}

if (!empty($_GET['get']) && !empty($_GET['item']) && $_GET['get']=='categedit' && $_GET['item']>0){
    $category_item = $live->getCategoryItem($_GET['item']);
    $smarty->assign("category_item", $category_item);
}

$smarty->assign("live_categories_list_id", $live->getLiveCategoriesListID());
$smarty->assign("live_categories_list", $live->getLiveCategoriesList());

// RUBRICS ///////// FINISH  ///////////////////////////////////////////////////////////////////

// SETTINGS ///////// BEGIN /////////////////////////////////////////////////////////////////
if(!empty($_POST['save_live_left_active_settings'])){
    if($_POST['is_active']==true) $is_active =1;
    else $is_active = 0;
    $elems = array(
        "set_value" => $is_active,
        "set_datetime_edit" => 'NOW()',
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "is_active_live_left"
    );
    if ($live->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if(!empty($_POST['save_live_left_count_settings'])){
    $_POST['cnv_value'] = intval($_POST['cnv_value']);
    $elems = array(
        "set_value" => $_POST['cnv_value'],
        "set_datetime_edit" => 'NOW()',
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "count_live_left"
    );
    if ($live->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if(!empty($_POST['save_live_count_settings'])){
    $_POST['cnv_value'] = intval($_POST['cnv_value']);
    $elems = array(
        "set_value" => $_POST['cnv_value'],
        "set_datetime_edit" => 'NOW()',
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "count_live_page"
    );
    if ($live->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if(!empty($_POST['save_live_index_count_settings'])){
    $_POST['cnv_value'] = intval($_POST['cnv_value']);
    $elems = array(
        "set_value" => $_POST['cnv_value'],
        "set_datetime_edit" => 'NOW()',
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "count_live_page_index"
    );
    if ($live->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if(!empty($_POST['save_live_max_char'])){
    $_POST['cnv_value'] = intval($_POST['cnv_value']);
    $elems = array(
        "set_value" => $_POST['cnv_value'],
        "set_datetime_edit" => 'NOW()',
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "count_live_max_char"
    );
    if ($live->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if (!empty($_GET['get']) && $_GET['get'] == 'settings') {
    $smarty->assign("live_settings_list", $live->getLiveSettings());

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
// SETTINGS ///////// FINISH /////////////////////////////////////////////////////////////////

// GALLERY ////////// BEGIN /////////////////////////////////////////////////////////////////
$live_id = !empty($_GET['item']) ? intval($_GET['item']) : 0;
$type = 'live';

if (!empty($_GET['cont']) && $_GET['cont'] == 4){

    // Photos for live
    if($_POST['add_new_live_photo']){
        if ($live->savePhoto($live_item, $photos)) $smarty->assign("ok_message", 'Фотография добавлена');
        else $smarty->assign("error_message", 'Фотография не добавлена');
    }

    if($_POST['save_edited_live_photo']){
        if ($_POST['ph_type_main']) $photos->resetTypeMainPhotos($_POST['n_id'], $type);
        $photos->updatePhotoGalleryCategory($_POST['ph_gallery_id'], $_POST['phg_phc_id']);
        if ($photos->saveEditedPhoto($_FILES['file_photo'], $_POST)) $smarty->assign("ok_message", 'Фотография обновлена');
        else $smarty->assign("error_message", 'Фотография не обновлена');
    }

    if($_POST['delete_edited_live_photo']){
        $_GET['item'] = intval($_GET['item']);
        if ($photos->deletePhoto($_POST['ph_id'])) header('Location: ./?show=live&get=edit&item='.$_GET['item'].'&cont=4');
        else $smarty->assign("error_message", 'Фотография не удалена');
    }

    if ($_GET['g_f_item']>0){
        $smarty->assign("photo_item", $photos->getPhotoItem($_GET['g_f_item']));
    }

    // Videos for live
    if($_POST['add_new_live_video']){
        if ($live->saveVideo($live_item, $videos)) $smarty->assign("ok_message", 'Видео добавлено');
        else $smarty->assign("error_message", 'Видео не добавлено');
    }

    if($_POST['save_edited_live_video']){
        $videos->updateVideoGalleryCategory($_POST['v_gallery_id'], $_POST['vg_vc_id']);
        if ($videos->saveEditedVideo($_POST)) $smarty->assign("ok_message", 'Видео обновлено');
        else $smarty->assign("error_message", 'Видео не обновлено');
    }

    if($_POST['save_edited_live_preview']){
        if ($videos->saveEditedVideoPreview($_FILES['file_photo_preview'], $_POST['v_id'])) $smarty->assign("ok_message", 'Превью для видео обновлено');
        else $smarty->assign("error_message", 'Превью для видео не обновлено');
    }

    if($_POST['delete_edited_live_video']){
        if ($videos->deleteVideo($_POST['v_id'])) header('Location: ./?show=live&get=edit&item='.$_GET['item'].'&cont=4');
        else $smarty->assign("error_message", 'Видео не удалено');
    }

    if ($_GET['g_v_item']>0){
        $smarty->assign("video_item", $videos->getVideoItem($_GET['g_v_item']));
    }
    $smarty->assign("live_photo_gallery", $photos->getTypePhotoGallery($live_id, $type));
    $smarty->assign("gallery_list", $photos->getPhotoGalleryList());
    $smarty->assign("photo_category_list", $photos->getPhotoCategoryList());
    $smarty->assign("live_video_gallery", $videos->getTypeVideoGallery($live_id, $type));
    $smarty->assign("gallery_list_video", $videos->getVideoGalleryList());
    $smarty->assign("video_category_list", $videos->getVideoCategoryList());

}

$smarty->assign("live_photo_list", $photos->getTypePhotoList($live_id, $type));
$smarty->assign("live_video_list", $videos->getTypeVideoList($live_id, $type));

// GALLERY ////////// FINISH //////////////////////////////////////////////////////////////////
