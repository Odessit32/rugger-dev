<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('../classes/admin/admin_redirects.php');

$redirects = new Redirects;

// REDIRECTS ///////// BEGIN ///////////////////////////////////////////////////////////////////
if(!empty($_POST['add_new_redirects'])){
    if ($redirects->createRedirects($_POST)) $smarty->assign("ok_message", 'Переадресация добавлена');
    else $smarty->assign("error_message", 'Переадресация не добавлена');
}

if(!empty($_POST['save_redirects_changes'])){
    if ($redirects->updateRedirects($_POST)) $smarty->assign("ok_message", 'Переадресация обновлена');
    else $smarty->assign("error_message", 'Переадресация не обновлена');
}

if(!empty($_POST['delete_redirects'])){
    if ($redirects->deleteRedirects($_POST['id'])) header('Location: ./?show=redirects'.(($_GET['page']>0)?'&page='.$_GET['page']:'').(($_GET['nnc']>0)?'&nnc='.$_GET['nnc']:''));
    else $smarty->assign("error_message", 'Переадресация не удалена');
}

if(!empty($_POST['update_redirects_file'])){
    if ($redirects->updateRedirectionFile()) $smarty->assign("ok_message", 'Файл Переадресации обновлен');
    else $smarty->assign("error_message", 'Файл Переадресации не обновлена');
}

if (!empty($_GET['get']) && !empty($_GET['item']) && $_GET['get']=='edit' && $_GET['item']>0){
    $redirects_item = $redirects->getRedirectsItem($_GET['item']);
    $smarty->assign("redirects_item", $redirects_item);
}
$page = (!empty($_GET['page'])) ? $_GET['page'] : 0;
$smarty->assign("redirects_list", $redirects->getRedirectsList(intval($page), 20, $nnc));
$smarty->assign("redirects_pages", $redirects->getRedirectsPages(intval($page), 20, $nnc));
$smarty->assign("authors", $redirects->getAuthorsList());

// REDIRECTS ///////// FINISH  ///////////////////////////////////////////////////////////////////
