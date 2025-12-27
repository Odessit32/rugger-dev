<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once(__DIR__.'/admin_notifications.php');

$notification = new Notification;

// NOTIFICATIONS ///////// BEGIN ///////////////////////////////////////////////////////////////////
if (!empty($_GET['get']) && $_GET['get'] == 'edit' && !empty($_GET['ok_message'])) {
    $smarty->assign("ok_message", 'Уведомление добавлено');
}
if(!empty($_POST['add_new_notification'])){
    $validate = $notification->validate($_POST);
    if (!empty($validate['status'])) {
        if ($new_id = $notification->create($_POST)) {
            header('Location: ./?show=notifications&get=edit&item=' . $new_id . '&ok_message=1' );
        } else $smarty->assign("error_message", 'Уведомление не добавлено');
    } else {
        $smarty->assign("error_message", $validate['message']);
    }
}

if(!empty($_POST['save_notification_changes'])){
    $validate = $notification->validate($_POST);
    if (!empty($validate['status'])) {
        if ($notification->update($_POST)) $smarty->assign("ok_message", 'Уведомление обновлено');
        else $smarty->assign("error_message", 'Уведомление не обновлено');
    } else {
        $smarty->assign("error_message", $validate['message']);
    }
}

if(!empty($_POST['delete_notification'])){
    if ($notification->delete($_POST['id'])) header('Location: ./?show=notifications'.((!empty($_GET['page']))?'&page='.$_GET['page']:''));
    else $smarty->assign("error_message", 'Уведомление не удалено');
}

if (!empty($_GET['get']) && !empty($_GET['item']) && $_GET['get']=='edit' && $_GET['item']>0){
    $notification_item = $notification->getItem($_GET['item']);
    $smarty->assign("notification_item", $notification_item);
}
$page = (!empty($_GET['page'])) ? $_GET['page'] : 0;
$smarty->assign("notification_list", $notification->getList(intval($page), 20));
$smarty->assign("notification_pages", $notification->getPages(intval($page), 20));
$smarty->assign("authors", $notification->getAuthorsList());

// NOTIFICATIONS ///////// FINISH  ///////////////////////////////////////////////////////////////////
