<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('../classes/admin/admin_videos.php');
$videos = new videos;

// ВИДЕО ФАЙЛЫ ////////////////// НАЧАЛО //////////////////////////////////////////////////////////////////

if($_POST['add_new_video']){
    if ($videos->saveVideo($_POST)) $smarty->assign("ok_message", 'Видео добавлено');
    else $smarty->assign("error_message", 'Видео не добавлено');
    //['v_code'], $_POST['v_folder'], $_POST['v_title'], $_POST['v_about'], $_POST['v_is_active'], $_POST['v_gallery_id']
}

if($_POST['save_edited_video']){
    if ($videos->saveEditedVideo($_POST)) $smarty->assign("ok_message", 'Видео обновлено');
    else $smarty->assign("error_message", 'Видео не обновлено');
}

if($_POST['save_edited_preview']){
    if ($videos->saveEditedVideoPreview($_FILES['file_photo_preview'], $_POST['v_id'])) $smarty->assign("ok_message", 'Превью для видео обновлено');
    else $smarty->assign("error_message", 'Превью для видео не обновлено');
}

if($_POST['delete_edited_video']){
    if ($videos->deleteVideo($_POST['v_id'])) header('Location: ./?show=videos');
    else $smarty->assign("error_message", 'Видео не удалено');
}

if ($_GET['get'] == '' or $_GET['get'] == 'videos'){
    //тут еще выборку папок и страниц для отображения...
    $smarty->assign("video_list_page", $videos->getVideoListPage($_GET['vg'], $_GET['page'], 12));
    $smarty->assign("video_pages", $videos->getVideoListPageList($_GET['vg'], $_GET['page'], 12));
}

if ($_GET['get'] == 'edit' and $_GET['item']>0){
    $smarty->assign("video_item", $videos->getVideoItem($_GET['item']));
}
// country_list и champ_list загружаются только для форм добавления/редактирования
if (!empty($_GET['get']) && ($_GET['get'] == 'add' || $_GET['get'] == 'edit' || $_GET['get'] == 'gallery_add' || $_GET['get'] == 'gallery_edit')) {
    $smarty->assign("country_list", $videos->getCountryList());
    $smarty->assign("champ_list", $videos->getChampList());
}
// ВИДЕО ФАЙЛЫ ////////////////// КОНЕЦ //////////////////////////////////////////////////////////////////

// GALLERY ///////////////////// НАЧАЛО //////////////////////////////////////////////////////////////////

if($_POST['delete_edited_video_gallery']){
    if ($videos->deleteVideoGallery($_POST['vg_id'])) header('Location: ./?show=videos&get=gallery');
    else $smarty->assign("error_message", 'Видео Галерея не удалена');
}

if($_POST['add_new_video_gallery']){
    if ($videos->addVideoGallery($_POST)) $smarty->assign("ok_message", 'Видео Галерея &laquo;'.$_POST['vg_title_ru'].'&raquo; добавлена');
    else $smarty->assign("error_message", 'Видео Галерея &laquo;'.$_POST['vg_title_ru'].'&raquo; не добавлена');
}

if($_POST['save_video_gallery']){
    if ($videos->saveEditedVideoGallery($_POST)) $smarty->assign("ok_message", 'Видео Галерея &laquo;'.$_POST['vg_title_ru'].'&raquo; отредактирована');
    else $smarty->assign("error_message", 'Видео Галерея &laquo;'.$_POST['vg_title_ru'].'&raquo; не отредактирована');
}

if($_POST['trans_videos_gallery']){
    if ($videos->transVideosFromGallery($_POST['vg_id'], $_POST['to_gallery_id'])) $smarty->assign("ok_message", 'Видео из Галереи &laquo;'.$_POST['vg_title'].'&raquo; перемещены');
    else $smarty->assign("error_message", 'Видео из Галереи &laquo;'.$_POST['vg_title'].'&raquo; не перемещены');
}

if ($_GET['get'] == 'gallery') {
    $smarty->assign("gallery_list_page", $videos->getVideoGalleryListPage($_GET['sort_list'], $_GET['page'], 20));
    $smarty->assign("gallery_pages", $videos->getVideoGalleryListPageList($_GET['sort_list'], $_GET['page'], 20));
}

if ( $_GET['get'] == 'gallery_add' or $_GET['get'] == 'gallery_edit'){
    $smarty->assign("category_list", $videos->getVideoCategoryList());
}
if ($_GET['get'] == 'gallery_edit' and $_GET['item'] > 0){
    $smarty->assign("gallery_item", $videos->getVideoGaleryItem($_GET['item']));
    $smarty->assign("gallery_list", $videos->getVideoGalleryList());
    $smarty->assign("video_list", $videos->getVideoList($_GET['item']));
}

// gallery_list загружается только для форм add/edit, не для списка (слишком много галерей)
if (!empty($_GET['get']) && ($_GET['get'] == 'add' || $_GET['get'] == 'edit')){
    $smarty->assign("gallery_list", $videos->getVideoGalleryList());
}

// GALLERY ///////////////////// КОНЕЦ ///////////////////////////////////////////////////////////////////

// РУБРИКИ ///////// НАЧАЛО //////////////////////////////////////////////////////////////////////////////

if($_POST['add_new_category']){
    if ($videos->createCategory($_POST)) $smarty->assign("ok_message", 'Рубрика добавлена');
    else $smarty->assign("error_message", 'Рубрика не добавлена');
}

if($_POST['save_category_changes']){
    if ($videos->updateCategory($_POST)) $smarty->assign("ok_message", 'Рубрика обновлена');
    else $smarty->assign("error_message", 'Рубрика не обновлена');
}

if($_POST['delete_category']){
    if ($videos->deleteCategory($_POST['vc_id'])) header('Location: ./?show=videos&get=categories');
    else $smarty->assign("error_message", 'Рубрика не удалена');
}

if ($_GET['get']=='categedit' and $_GET['item']>0){
    $category_item = $videos->getCategoryItem($_GET['item']);
    $smarty->assign("category_item", $category_item);
}

// videos_categories_list загружается только для раздела рубрик
if (!empty($_GET['get']) && ($_GET['get'] == 'categories' || $_GET['get'] == 'categedit' || $_GET['get'] == 'categadd')) {
    $smarty->assign("videos_categories_list_id", $videos->getVideosCategoriesListID());
    $smarty->assign("videos_categories_list", $videos->getVideosCategoriesList());
}

// РУБРИКИ ///////// КОНЕЦ  ////////////////////////////////////////////////////////////////////////////////

// Информер и Настройки //////// НАЧАЛО ///////////////////////////////////////////////////////////////////

if ($_GET['get']=='informer'){
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

    if($_POST['save_informer_settings']){
        if ($videos->saveInformer($_POST['is_active'])) $smarty->assign("ok_message", 'Информер обновлен');
        else $smarty->assign("error_message", 'Информер не обновлен');
    }

    if($_POST['save_video_informer_count_settings']){
        $_POST['cnv_value'] = intval($_POST['cnv_value']);
        $elems = array(
            "set_value" => $_POST['cnv_value'],
            "set_datetime_edit" => 'NOW()',
            "set_author" => USER_ID
        );
        $condition = array(
            "set_name" => "count_video_informer"
        );
        if ($videos->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
        else $smarty->assign("error_message", 'Настройки не обновлены');
    }

    if($_POST['save_video_count_settings']){
        $_POST['cnv_value'] = intval($_POST['cnv_value']);
        $elems = array(
            "set_value" => $_POST['cnv_value'],
            "set_datetime_edit" => 'NOW()',
            "set_author" => USER_ID
        );
        $condition = array(
            "set_name" => "count_video_page"
        );
        if ($videos->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
        else $smarty->assign("error_message", 'Настройки не обновлены');
    }

    if($_POST['save_video_index_count_settings']){
        $_POST['cnv_value'] = intval($_POST['cnv_value']);
        $elems = array(
            "set_value" => $_POST['cnv_value'],
            "set_datetime_edit" => 'NOW()',
            "set_author" => USER_ID
        );
        $condition = array(
            "set_name" => "count_video_page_index"
        );
        if ($videos->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
        else $smarty->assign("error_message", 'Настройки не обновлены');
    }

    $smarty->assign("conf_vars", $conf->getConfVars());
    $smarty->assign("informer", $videos->getInformer());
    $smarty->assign("video_settings_list", $videos->getVideoSettings());
}

// Информер и Настройки //////// КОНЕЦ ////////////////////////////////////////////////////////////////////

?>