<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('classes/feedback.php');
$feedback = new feedback;

if (!empty($url->rest_path)){
    $s = 0;
    // Сообщение /////////////////////////////////////////////////////
    if ($url->rest_path[$s] and substr($url->rest_path[$s], 32, 1) == '-') {
        list($locator, $id) = explode("-", $url->rest_path[$s], 2);
        $id = intval($id);
        $message_item = $feedback->getMessageItem($id, $locator);
        $smarty->assign("message_item", $message_item);
        $s++;
    }
}

if (!empty($_POST['send_message'])) {
    include_once('classes/mail.php');

    $message = $feedback->saveMessage();
    $smarty->assign("message", $message);
}

// Баннеры
include_once('classes/banners.php');
$banners = new banners;
$pbi = $banners->getPageBanInfList($url->page['p_id'], $url->page['p_c_banners']);
$smarty->assign("pbi", $pbi);

if (!empty($pbi['classes'])) {
    foreach ($pbi['classes'] as $item) {
        if (!empty($item) && file_exists('classes/'.$item)){
            include_once('classes/'.$item);
        }
    }
}