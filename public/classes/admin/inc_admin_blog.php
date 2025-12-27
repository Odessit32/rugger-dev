<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('../classes/admin/admin_blog.php');
include_once('../classes/admin/admin_photos.php');
include_once('../classes/admin/admin_videos.php');

$blog = new Blog;
$photos = new photos;
$videos = new videos;

// BLOG POSTS ///////// BEGIN ///////////////////////////////////////////////////////////////////
if(!empty($_POST['add_new_post'])){
    if ($blog->createPost($_POST)) $smarty->assign("ok_message", 'Запись добавлена');
    else $smarty->assign("error_message", 'Запись не добавлена');
}

if(!empty($_POST['save_post_changes'])){
    if ($blog->updatePost($_POST)) $smarty->assign("ok_message", 'Запись обновлена');
    else $smarty->assign("error_message", 'Запись не обновлена');
}

if(!empty($_POST['delete_post'])){
    if ($blog->deletePost($_POST['id'])) header('Location: ./?show=blog'.((!empty($_GET['page']))?'&page='.$_GET['page']:'').((!empty($_GET['nnc']))?'&nnc='.$_GET['nnc']:''));
    else $smarty->assign("error_message", 'Запись не удалена');
}

if (!empty($_GET['get']) && !empty($_GET['item']) && $_GET['get']=='edit' && $_GET['item']>0){
    $post_item = $blog->getPostItem($_GET['item']);
    $smarty->assign("post_item", $post_item);

    // meta SEO functions start
    $meta_seo_item = $post_item['id'];
    $meta_seo_item_type = 'blog';
    include_once('../classes/admin/inc_admin_meta_seo.php');
    // meta SEO functions finish
}
$smarty->assign("country_list", $blog->getCountryList());
$smarty->assign("championship_list", $blog->getChampionshipList());
$smarty->assign("champ_list", $blog->getChampList());
$page = (!empty($_GET['page'])) ? $_GET['page'] : 0;
$nnc = (!empty($_GET['nnc'])) ? $_GET['nnc'] : 0;
$smarty->assign("post_list", $blog->getPostList(intval($page), 20, $nnc));
$smarty->assign("post_pages", $blog->getPostPages(intval($page), 20, $nnc));
$smarty->assign("authors", $blog->getAuthorsList());

// BLOG POSTS ///////// FINISH  ///////////////////////////////////////////////////////////////////

// RUBRICS ///////// BEGIN ///////////////////////////////////////////////////////////////////
if(!empty($_POST['add_new_category'])){
    if ($blog->createCategory($_POST)) $smarty->assign("ok_message", 'Рубрика добавлена');
    else $smarty->assign("error_message", 'Рубрика не добавлена');
}

if(!empty($_POST['save_category_changes'])){
    if ($blog->updateCategory($_POST)) $smarty->assign("ok_message", 'Рубрика обновлена');
    else $smarty->assign("error_message", 'Рубрика не обновлена');
}

if(!empty($_POST['delete_category'])){
    if ($blog->deleteCategory($_POST['nс_id'])) header('Location: ./?show=blog&get=categories');
    else $smarty->assign("error_message", 'Рубрика не удалена');
}

if (!empty($_GET['get']) && !empty($_GET['item']) && $_GET['get']=='categedit' && $_GET['item']>0){
    $category_item = $blog->getCategoryItem($_GET['item']);
    $smarty->assign("category_item", $category_item);
}

$smarty->assign("blog_categories_list_id", $blog->getPostCategoriesListID());
$smarty->assign("blog_categories_list", $blog->getPostCategoriesList());

// RUBRICS ///////// FINISH  ///////////////////////////////////////////////////////////////////

// SETTINGS ///////// BEGIN /////////////////////////////////////////////////////////////////
if(!empty($_POST['save_blog_left_active_settings'])){
    if($_POST['is_active']==true) $is_active =1;
    else $is_active = 0;
    $elems = array(
        "set_value" => $is_active,
        "set_datetime_edit" => 'NOW()',
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "is_active_blog_left"
    );
    if ($blog->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if(!empty($_POST['save_blog_left_count_settings'])){
    $_POST['cnv_value'] = intval($_POST['cnv_value']);
    $elems = array(
        "set_value" => $_POST['cnv_value'],
        "set_datetime_edit" => 'NOW()',
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "count_blog_left"
    );
    if ($blog->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if(!empty($_POST['save_blog_count_settings'])){
    $_POST['cnv_value'] = intval($_POST['cnv_value']);
    $elems = array(
        "set_value" => $_POST['cnv_value'],
        "set_datetime_edit" => 'NOW()',
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "count_blog_page"
    );
    if ($blog->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if(!empty($_POST['save_blog_index_count_settings'])){
    $_POST['cnv_value'] = intval($_POST['cnv_value']);
    $elems = array(
        "set_value" => $_POST['cnv_value'],
        "set_datetime_edit" => 'NOW()',
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "count_blog_page_index"
    );
    if ($blog->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if(!empty($_POST['save_blog_max_char'])){
    $_POST['cnv_value'] = intval($_POST['cnv_value']);
    $elems = array(
        "set_value" => $_POST['cnv_value'],
        "set_datetime_edit" => 'NOW()',
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "count_blog_max_char"
    );
    if ($blog->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if (!empty($_GET['get']) && $_GET['get'] == 'settings') {
    $smarty->assign("blog_settings_list", $blog->getListSettings());

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
// SETTINGS ///////// FINISH /////////////////////////////////////////////////////////////////

// GALLERY ////////// BEGIN /////////////////////////////////////////////////////////////////
$post_id = !empty($_GET['item']) ? intval($_GET['item']) : 0;
$type = 'blog';

if (!empty($_GET['cont']) && $_GET['cont'] == 4){

    // Photos for blog
    if(!empty($_POST['add_new_blog_photo'])){
        if ($blog->savePhoto($post_item, $photos)) $smarty->assign("ok_message", 'Фотография добавлена');
        else $smarty->assign("error_message", 'Фотография не добавлена');
    }

    if(!empty($_POST['save_edited_blog_photo'])){
        if ($_POST['ph_type_main']) $photos->resetTypeMainPhotos($_POST['n_id'], $type);
        $photos->updatePhotoGalleryCategory($_POST['ph_gallery_id'], $_POST['phg_phc_id']);
        if ($photos->saveEditedPhoto($_FILES['file_photo'], $_POST)) $smarty->assign("ok_message", 'Фотография обновлена');
        else $smarty->assign("error_message", 'Фотография не обновлена');
    }

    if(!empty($_POST['delete_edited_blog_photo'])){
        $_GET['item'] = intval($_GET['item']);
        if ($photos->deletePhoto($_POST['ph_id'])) header('Location: ./?show=blog&get=edit&item='.$_GET['item'].'&cont=4');
        else $smarty->assign("error_message", 'Фотография не удалена');
    }

    if (!empty($_GET['g_f_item'])){
        $smarty->assign("photo_item", $photos->getPhotoItem($_GET['g_f_item']));
    }

    // Videos for blog
    if(!empty($_POST['add_new_blog_video'])){
        if ($blog->saveVideo($post_item, $videos)) $smarty->assign("ok_message", 'Видео добавлено');
        else $smarty->assign("error_message", 'Видео не добавлено');
    }

    if(!empty($_POST['save_edited_blog_video'])){
        $videos->updateVideoGalleryCategory($_POST['v_gallery_id'], $_POST['vg_vc_id']);
        if ($videos->saveEditedVideo($_POST)) $smarty->assign("ok_message", 'Видео обновлено');
        else $smarty->assign("error_message", 'Видео не обновлено');
    }

    if(!empty($_POST['save_edited_blog_preview'])){
        if ($videos->saveEditedVideoPreview($_FILES['file_photo_preview'], $_POST['v_id'])) $smarty->assign("ok_message", 'Превью для видео обновлено');
        else $smarty->assign("error_message", 'Превью для видео не обновлено');
    }

    if(!empty($_POST['delete_edited_blog_video'])){
        if ($videos->deleteVideo($_POST['v_id'])) header('Location: ./?show=blog&get=edit&item='.$_GET['item'].'&cont=4');
        else $smarty->assign("error_message", 'Видео не удалено');
    }

    if (!empty($_GET['g_v_item'])){
        $smarty->assign("video_item", $videos->getVideoItem($_GET['g_v_item']));
    }
    $smarty->assign("blog_photo_gallery", $photos->getTypePhotoGallery($post_id, $type));
    $smarty->assign("gallery_list", $photos->getPhotoGalleryList());
    $smarty->assign("photo_category_list", $photos->getPhotoCategoryList());
    $smarty->assign("blog_video_gallery", $videos->getTypeVideoGallery($post_id, $type));
    $smarty->assign("gallery_list_video", $videos->getVideoGalleryList());
    $smarty->assign("video_category_list", $videos->getVideoCategoryList());

}

$smarty->assign("blog_photo_list", $photos->getTypePhotoList($post_id, $type));
$smarty->assign("blog_video_list", $videos->getTypeVideoList($post_id, $type));

// GALLERY ////////// FINISH //////////////////////////////////////////////////////////////////
