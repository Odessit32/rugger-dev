<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once(__DIR__.'/admin_photos.php');
$photos = new photos;

// Фотографии ////////////////// НАЧАЛО ///////////////////////////////////////////////////////////////////

if(!empty($_POST['add_new_photo'])){
    if ($photos->savePhoto((!empty($_FILES['file_photo']))?$_FILES['file_photo']:false, $_POST)) $smarty->assign("ok_message", 'Фотография добавлена');
    else $smarty->assign("error_message", 'Фотография не добавлена');
}

if(!empty($_POST['save_edited_photo'])){
    if ($photos->saveEditedPhoto($_FILES['file_photo'], $_POST)) $smarty->assign("ok_message", 'Фотография обновлена');
    else $smarty->assign("error_message", 'Фотография не обновлена');
}

if(!empty($_POST['delete_edited_photo'])){
    if ($photos->deletePhoto($_POST['ph_id'])) header('Location: ./?show=photos');
    else $smarty->assign("error_message", 'Фотография не удалена');
}

if (empty($_GET['get']) || $_GET['get'] == 'photos'){
    //тут еще выборку папок и страниц для отображения...
    $smarty->assign("photo_list_page", $photos->getPhotoListPage((!empty($_GET['phg']))?$_GET['phg']:'', (!empty($_GET['page']))?$_GET['page']:'', 12));
    $smarty->assign("photo_pages", $photos->getPhotoListPageList((!empty($_GET['phg']))?$_GET['phg']:'', (!empty($_GET['page']))?$_GET['page']:'', 12));
}
if (!empty($_GET['item']) && $_GET['get'] == 'edit'){
    $smarty->assign("photo_item", $photos->getPhotoItem($_GET['item']));
}
// country_list и champ_list загружаются только для форм добавления/редактирования
if (!empty($_GET['get']) && ($_GET['get'] == 'add' || $_GET['get'] == 'edit' || $_GET['get'] == 'gallery_add' || $_GET['get'] == 'gallery_edit')) {
    $smarty->assign("country_list", $photos->getCountryList());
    $smarty->assign("champ_list", $photos->getChampList());
}
// Фотографии ////////////////// КОНЕЦ ///////////////////////////////////////////////////////////////////

// GALLERY ///////////////////// НАЧАЛО //////////////////////////////////////////////////////////////////

if(!empty($_POST['delete_edited_photo_gallery'])){
    if ($photos->deletePhotoGallery($_POST['phg_id'])) header('Location: ./?show=photos&get=gallery');
    else $smarty->assign("error_message", 'Фото Галерея не удалена');
}

if(!empty($_POST['add_new_photo_gallery'])){
    if ($photos->addPhotoGallery($_POST)) $smarty->assign("ok_message", 'Фото Галерея &laquo;'.$_POST['phg_title_ru'].'&raquo; добавлена');
    else $smarty->assign("error_message", 'Фото Галерея &laquo;'.$_POST['phg_title_ru'].'&raquo; не добавлена');
}

if(!empty($_POST['save_photo_gallery'])){
    if ($photos->saveEditedPhotoGallery($_POST)) $smarty->assign("ok_message", 'Фото Галерея &laquo;'.$_POST['phg_title_ru'].'&raquo; отредактирована');
    else $smarty->assign("error_message", 'Фото Галерея &laquo;'.$_POST['phg_title_ru'].'&raquo; не отредактирована');
}

if(!empty($_POST['trans_photos_gallery'])){
    if ($photos->transPhotosFromGallery($_POST['phg_id'], $_POST['to_gallery_id'])) $smarty->assign("ok_message", 'Фото из Галереи &laquo;'.$_POST['phg_title_ru'].'&raquo; перемещены');
    else $smarty->assign("error_message", 'Фото из Галереи &laquo;'.$_POST['phg_title_ru'].'&raquo; не перемещены');
}

if (!empty($_GET['get']) && $_GET['get'] == 'gallery') {
    $_GET['sort_list'] = (!empty($_GET['sort_list']))?$_GET['sort_list']:'';
    $_GET['page'] = (!empty($_GET['page']))?$_GET['page']:'';
    $smarty->assign("gallery_list_page", $photos->getPhotoGalleryListPage($_GET['sort_list'], $_GET['page'], 20));
    $smarty->assign("gallery_pages", $photos->getPhotoGalleryListPageList($_GET['sort_list'], $_GET['page'], 20));
}

if (!empty($_GET['get']) && ($_GET['get'] == 'gallery_add' or $_GET['get'] == 'gallery_edit')){
    if (!empty($_GET['item'])) $smarty->assign("gallery_item", $photos->getPhotoGaleryItem($_GET['item']));
    $smarty->assign("gallery_list", $photos->getPhotoGalleryList());
    $smarty->assign("category_list", $photos->getPhotoCategoryList());
}

if (!empty($_GET['item']) && $_GET['get'] == 'gallery_edit'){
    $smarty->assign("photo_list", $photos->getPhotoList($_GET['item']));
}

// gallery_list загружается только для форм add/edit, не для списка (слишком много галерей - 67000+)
if (!empty($_GET['get']) && ($_GET['get'] == 'add' || $_GET['get'] == 'edit')){
    $smarty->assign("gallery_list", $photos->getPhotoGalleryList());
}
// GALLERY ///////////////////// КОНЕЦ ///////////////////////////////////////////////////////////////////

// РУБРИКИ ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////

if(!empty($_POST['add_new_category'])){
    if ($photos->createCategory($_POST)) $smarty->assign("ok_message", 'Рубрика добавлена');
    else $smarty->assign("error_message", 'Рубрика не добавлена');
}

if(!empty($_POST['save_category_changes'])){
    if ($photos->updateCategory($_POST)) $smarty->assign("ok_message", 'Рубрика обновлена');
    else $smarty->assign("error_message", 'Рубрика не обновлена');
}

if(!empty($_POST['delete_category'])){
    if ($photos->deleteCategory($_POST['phc_id'])) header('Location: ./?show=photos&get=categories');
    else $smarty->assign("error_message", 'Рубрика не удалена');
}

if (!empty($_GET['item']) && $_GET['get']=='categedit'){
    $category_item = $photos->getCategoryItem($_GET['item']);
    $smarty->assign("category_item", $category_item);
}

// photos_categories_list загружается только для раздела рубрик
if (!empty($_GET['get']) && ($_GET['get'] == 'categories' || $_GET['get'] == 'categedit' || $_GET['get'] == 'categadd')) {
    $smarty->assign("photos_categories_list_id", $photos->getPhotosCategoriesListID());
    $smarty->assign("photos_categories_list", $photos->getPhotosCategoriesList());
}

// РУБРИКИ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// Информер и Настройки //////// НАЧАЛО ///////////////////////////////////////////////////////////////////

if (!empty($_GET['get']) && $_GET['get']=='informer'){
    include_once(__DIR__.'/admin_conf_vars.php');
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

    if(!empty($_POST['save_informer_settings'])){
        if ($photos->saveInformer($_POST['is_active'])) $smarty->assign("ok_message", 'Информер обновлен');
        else $smarty->assign("error_message", 'Информер не обновлен');
    }

    if(!empty($_POST['save_photo_informer_count_settings'])){
        $_POST['cnv_value'] = intval($_POST['cnv_value']);
        $elems = array(
            "set_value" => $_POST['cnv_value'],
            "set_datetime_edit" => 'NOW()',
            "set_author" => USER_ID
        );
        $condition = array(
            "set_name" => "count_photo_informer"
        );
        if ($photos->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
        else $smarty->assign("error_message", 'Настройки не обновлены');
    }

    if(!empty($_POST['save_photo_count_settings'])){
        $_POST['cnv_value'] = intval($_POST['cnv_value']);
        $elems = array(
            "set_value" => $_POST['cnv_value'],
            "set_datetime_edit" => 'NOW()',
            "set_author" => USER_ID
        );
        $condition = array(
            "set_name" => "count_photo_page"
        );
        if ($photos->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
        else $smarty->assign("error_message", 'Настройки не обновлены');
    }

    if(!empty($_POST['save_photo_index_count_settings'])){
        $_POST['cnv_value'] = intval($_POST['cnv_value']);
        $elems = array(
            "set_value" => $_POST['cnv_value'],
            "set_datetime_edit" => 'NOW()',
            "set_author" => USER_ID
        );
        $condition = array(
            "set_name" => "count_photo_page_index"
        );
        if ($photos->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
        else $smarty->assign("error_message", 'Настройки не обновлены');
    }

    $smarty->assign("informer", $photos->getInformer());
    $smarty->assign("conf_vars", $conf->getConfVars());
    $smarty->assign("photo_settings_list", $photos->getPhotoSettings());
}

// Информер и Настройки //////// КОНЕЦ ////////////////////////////////////////////////////////////////////
