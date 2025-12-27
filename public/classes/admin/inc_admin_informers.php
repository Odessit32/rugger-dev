<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('../classes/admin/admin_informers.php');
$informers = new informers;

// informers начало ///////////////////////////////////////////////////////////////////
if(!empty($_POST['add_new_informer'])){
    if ($informers->addInformer($_POST)) $smarty->assign("ok_message", 'Информер добавлен');
    else $smarty->assign("error_message", 'Информер не добавлен');
}

if(!empty($_POST['save_edited_informer'])){
    if ($informers->saveEditedInformer($_POST['i_id'], $_POST)) $smarty->assign("ok_message", 'Информер обновлен');
    else $smarty->assign("error_message", 'Информер не обновлен');
}

if(!empty($_POST['delete_edited_informer'])){
    if ($informers->deleteInformer($_POST['i_id'])) header('Location: ./?show=informers');
    else $smarty->assign("error_message", 'Информер не удален');
}

if(!empty($_POST['save_edited_informer_pages'])){
    if ($informers->saveInformerPage($_POST['i_id'], $_POST['page_id'], $_POST['place'])) $smarty->assign("ok_message", 'Информер присоединен к страницам');
    else $smarty->assign("error_message", 'Информер не присоединен к страницам');
}

if (empty($_GET['get'])){
    //тут еще выборку папок и страниц для отображения...

    $smarty->assign("informers_list", $informers->getInformersList());
}
if (!empty($_GET['get']) && $_GET['get'] == 'edit' && !empty($_GET['item'])){
    include_once('../classes/admin/admin_page.php');
    $page = new page;
    $smarty->assign("pages_parent", $page->getPagesList());

    $smarty->assign("informer_page_list", $informers->getInformerPageList($_GET['item']));
    $smarty->assign("informer_item", $informers->getInformerItem($_GET['item']));
}

// informers конец ///////////////////////////////////////////////////////////////////
