<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('../classes/admin/admin_banners.php');
$banners = new banners;

// БАННЕРЫ начало ///////////////////////////////////////////////////////////////////
if(!empty($_POST['add_new_banner'])){
    if ($banners->addBanner($_FILES['file_banner'], '/', $_POST)) $smarty->assign("ok_message", 'Баннер добавлен');
    else $smarty->assign("error_message", 'Баннер не добавлен');
}

if(!empty($_POST['save_edited_banner'])){
    if ($banners->saveEditedBanner($_POST['b_id'], $_FILES['file_banner'], '/', $_POST)) $smarty->assign("ok_message", 'Баннер обновлен');
    else $smarty->assign("error_message", 'Баннер не обновлен');
}

if(!empty($_POST['delete_edited_banner'])){
    if ($banners->deleteBanner($_POST['b_id'])) header('Location: ./?show=banners');
    else $smarty->assign("error_message", 'Баннер не удален');
}

if(!empty($_POST['save_edited_banner_pages'])){
    if ($banners->saveBannerPage($_POST['b_id'], $_POST['page_id'], $_POST['pbi_place'], $_POST['pbi_order'], $_POST['pbi_display_type'])) $smarty->assign("ok_message", 'Баннер присоединен к страницам');
    else $smarty->assign("error_message", 'Баннер не присоединен к страницам');
}

if (empty($_GET['get'])){
    //тут еще выборку папок и страниц для отображения...

    $smarty->assign("banners_list", $banners->getBannersList());
}
if (!empty($_GET['get']) && $_GET['get'] == 'edit' && !empty($_GET['item'])){
    include_once('../classes/admin/admin_page.php');
    $page = new page;
    $smarty->assign("pages_parent", $page->getPagesList());

    $smarty->assign("banner_page_list", $banners->getBannerPageList($_GET['item']));
    $banner_item = $banners->getBannerItem($_GET['item']);
    if ($banner_item) $smarty->assign("banner_item", $banner_item);
    else header('Location: ./?show=banners');
}

// БАННЕРЫ конец ///////////////////////////////////////////////////////////////////
?>