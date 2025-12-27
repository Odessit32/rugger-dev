<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once(__DIR__.'/admin_news.php');
include_once(__DIR__.'/admin_photos.php');
include_once(__DIR__.'/admin_videos.php');

$news = new news;
$photos = new photos;
$videos = new videos;

// NEWS ///////// BEGIN ///////////////////////////////////////////////////////////////////
if (!empty($_GET['get']) && $_GET['get'] == 'edit' && !empty($_GET['ok_message'])) {
    $smarty->assign("ok_message", 'Новость добавлена');
}
if(!empty($_POST['add_new_news']) || !empty($_POST['add_new_news_gallery'])){
    $validate = $news->validateSave($_POST);
    if (!empty($validate['status'])) {
        if ($new_id = $news->createNews($_POST)) {
            header('Location: ./?show=news&get=edit&item=' . $new_id . '&ok_message=1' . ((!empty($_POST['add_new_news_gallery'])) ? '&cont=4' : ''));
        } else $smarty->assign("error_message", 'Новость не добавлена');
    } else {
        $smarty->assign("error_message", $validate['message']);
    }
}

if(!empty($_POST['save_news_changes'])){
    $validate = $news->validateSave($_POST);
    if (!empty($validate['status'])) {
        if ($news->updateNews($_POST)) $smarty->assign("ok_message", 'Новость обновлена');
        else $smarty->assign("error_message", 'Новость не обновлена');
    } else {
        $smarty->assign("error_message", $validate['message']);
    }
}

if(!empty($_POST['delete_news'])){
    if ($news->deleteNews($_POST['n_id'])) header('Location: ./?show=news'.((!empty($_GET['page']))?'&page='.$_GET['page']:'').((!empty($_GET['nnc']))?'&nnc='.$_GET['nnc']:''));
    else $smarty->assign("error_message", 'Новость не удалена');
}

if (!empty($_GET['get']) && !empty($_GET['item']) && $_GET['get']=='edit' && $_GET['item']>0){
    $news_item = $news->getNewsItem($_GET['item']);
    $smarty->assign("news_item", $news_item);

    // meta SEO functions start
    $meta_seo_item = $news_item['n_id'];
    $meta_seo_item_type = 'news';
    include_once(__DIR__.'/inc_admin_meta_seo.php');
    // meta SEO functions finish
}
$smarty->assign("country_list", $news->getCountryList());
$smarty->assign("championship_list", $news->getChampionshipList());
$smarty->assign("champ_list", $news->getChampList());
$page = (!empty($_GET['page'])) ? $_GET['page'] : 0;
$nnc = (!empty($_GET['nnc'])) ? $_GET['nnc'] : 0;
$smarty->assign("news_list", $news->getNewsList(intval($page), 20, $nnc));
$smarty->assign("news_pages", $news->getNewsPages(intval($page), 20, $nnc));
$smarty->assign("authors", $news->getAuthorsList());

// NEWS ///////// FINISH  ///////////////////////////////////////////////////////////////////

// RUBRICS ///////// BEGIN ///////////////////////////////////////////////////////////////////
if(!empty($_POST['add_new_category'])){
    if ($news->createCategory($_POST)) $smarty->assign("ok_message", 'Рубрика добавлена');
    else $smarty->assign("error_message", 'Рубрика не добавлена');
}

if(!empty($_POST['save_category_changes'])){
    if ($news->updateCategory($_POST)) $smarty->assign("ok_message", 'Рубрика обновлена');
    else $smarty->assign("error_message", 'Рубрика не обновлена');
}

if(!empty($_POST['delete_category'])){
    if ($news->deleteCategory($_POST['nс_id'])) header('Location: ./?show=news&get=categories');
    else $smarty->assign("error_message", 'Рубрика не удалена');
}

if (!empty($_GET['get']) && !empty($_GET['item']) && $_GET['get']=='categedit' && $_GET['item']>0){
    $category_item = $news->getCategoryItem($_GET['item']);
    $smarty->assign("category_item", $category_item);
}

$smarty->assign("news_categories_list_id", $news->getNewsCategoriesListID());
$smarty->assign("news_categories_list", $news->getNewsCategoriesList());

// RUBRICS ///////// FINISH  ///////////////////////////////////////////////////////////////////

// SETTINGS ///////// BEGIN /////////////////////////////////////////////////////////////////
if(!empty($_POST['save_news_left_active_settings'])){
    if($_POST['is_active']==true) $is_active =1;
    else $is_active = 0;
    $elems = array(
        "set_value" => $is_active,
        "set_datetime_edit" => 'NOW()',
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "is_active_news_left"
    );
    if ($news->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if(!empty($_POST['save_news_left_count_settings'])){
    $_POST['cnv_value'] = intval($_POST['cnv_value']);
    $elems = array(
        "set_value" => $_POST['cnv_value'],
        "set_datetime_edit" => 'NOW()',
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "count_news_left"
    );
    if ($news->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if(!empty($_POST['save_news_count_settings'])){
    $_POST['cnv_value'] = intval($_POST['cnv_value']);
    $elems = array(
        "set_value" => $_POST['cnv_value'],
        "set_datetime_edit" => 'NOW()',
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "count_news_page"
    );
    if ($news->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if(!empty($_POST['save_news_index_count_settings'])){
    $_POST['cnv_value'] = intval($_POST['cnv_value']);
    $elems = array(
        "set_value" => $_POST['cnv_value'],
        "set_datetime_edit" => 'NOW()',
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "count_news_page_index"
    );
    if ($news->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if(!empty($_POST['save_news_max_char'])){
    $_POST['cnv_value'] = intval($_POST['cnv_value']);
    $elems = array(
        "set_value" => $_POST['cnv_value'],
        "set_datetime_edit" => 'NOW()',
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "count_news_max_char"
    );
    if ($news->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if (!empty($_GET['get']) && $_GET['get'] == 'settings') {
    $smarty->assign("news_settings_list", $news->getSettings());

    // Заголовки ////////////////////////////////////////////////////////////////
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

    $smarty->assign("conf_vars", $conf->getConfVars());
}
// SETTINGS ///////// FINISH /////////////////////////////////////////////////////////////////

// GALLERY ////////// BEGIN /////////////////////////////////////////////////////////////////
$news_id = !empty($_GET['item']) ? intval($_GET['item']) : 0;
$type = 'news';

if (!empty($_GET['cont']) && $_GET['cont'] == 4){

    // Photos for news
    if(!empty($_POST['add_new_news_photo'])){
        if ($news->savePhoto($news_item, $photos)) $smarty->assign("ok_message", 'Фотография добавлена');
        else $smarty->assign("error_message", 'Фотография не добавлена');
    }

    if(!empty($_POST['save_edited_news_photo'])){
        if ($_POST['ph_type_main']) $photos->resetTypeMainPhotos($_POST['n_id'], $type);
        $photos->updatePhotoGalleryCategory($_POST['ph_gallery_id'], $_POST['phg_phc_id']);
        if ($photos->saveEditedPhoto($_FILES['file_photo'], $_POST)) $smarty->assign("ok_message", 'Фотография обновлена');
        else $smarty->assign("error_message", 'Фотография не обновлена');
    }

    if(!empty($_POST['delete_edited_news_photo'])){
        $_GET['item'] = intval($_GET['item']);
        if ($photos->deletePhoto($_POST['ph_id'])) header('Location: ./?show=news&get=edit&item='.$_GET['item'].'&cont=4');
        else $smarty->assign("error_message", 'Фотография не удалена');
    }

    if (!empty($_GET['g_f_item'])){
        $smarty->assign("photo_item", $photos->getPhotoItem($_GET['g_f_item']));
    }

    // Videos for news
    if(!empty($_POST['add_new_news_video'])){
        if ($news->saveVideo($news_item, $videos)) $smarty->assign("ok_message", 'Видео добавлено');
        else $smarty->assign("error_message", 'Видео не добавлено');
    }

    if(!empty($_POST['save_edited_news_video'])){
        $videos->updateVideoGalleryCategory($_POST['v_gallery_id'], $_POST['vg_vc_id']);
        if ($videos->saveEditedVideo($_POST)) $smarty->assign("ok_message", 'Видео обновлено');
        else $smarty->assign("error_message", 'Видео не обновлено');
    }

    if(!empty($_POST['save_edited_news_preview'])){
        if ($videos->saveEditedVideoPreview($_FILES['file_photo_preview'], $_POST['v_id'])) $smarty->assign("ok_message", 'Превью для видео обновлено');
        else $smarty->assign("error_message", 'Превью для видео не обновлено');
    }

    if(!empty($_POST['delete_edited_news_video'])){
        if ($videos->deleteVideo($_POST['v_id'])) header('Location: ./?show=news&get=edit&item='.$_GET['item'].'&cont=4');
        else $smarty->assign("error_message", 'Видео не удалено');
    }

    if (!empty($_GET['g_v_item'])){
        $smarty->assign("video_item", $videos->getVideoItem($_GET['g_v_item']));
    }
    $smarty->assign("news_photo_gallery", $photos->getTypePhotoGallery($news_id, $type));
    $smarty->assign("news_video_gallery", $videos->getTypeVideoGallery($news_id, $type));
    $smarty->assign("photo_category_list", $photos->getPhotoCategoryList());
    $smarty->assign("video_category_list", $videos->getVideoCategoryList());

    // Загружаем списки всех галерей только при редактировании (редкий кейс - добавить в другую галерею)
    if (!empty($_GET['g_f_item']) || !empty($_GET['g_v_item'])) {
        $smarty->assign("gallery_list", $photos->getPhotoGalleryList());
        $smarty->assign("gallery_list_video", $videos->getVideoGalleryList());
    }

}

$smarty->assign("news_photo_list", $photos->getTypePhotoList($news_id, $type));
$smarty->assign("news_video_list", $videos->getTypeVideoList($news_id, $type));

// GALLERY ////////// FINISH //////////////////////////////////////////////////////////////////
