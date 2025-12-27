<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('../classes/admin/admin_feedback.php');

$feedback = new feedback;

// feedback ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////

if($_POST['save_feedback_changes']){
    if ($feedback->updateFeedback($_POST)) $smarty->assign("ok_message", 'Сообщение обновлено');
    else $smarty->assign("error_message", 'Сообщение не обновлено');
}

if($_POST['send_feedback_response']){
    include_once('../classes/mail.php');
    if ($feedback->sendFeedbackResponse($_POST)) $smarty->assign("ok_message", 'Ответ на сообщение обновлено');
    else $smarty->assign("error_message", 'Ответ на сообщение обновлено');
}

if($_POST['delete_feedback']){
    if ($feedback->deleteFeedback($_POST['fb_id'])) header('Location: ./?show=feedback');
    else $smarty->assign("error_message", 'Сообщение не удалено');
}

if ($_GET['get']=='edit' and $_GET['item']>0){
    $feedback_item = $feedback->getFeedbackItem($_GET['item']);
    $smarty->assign("feedback_item", $feedback_item);
}

$smarty->assign("feedback_list", $feedback->getFeedbackList(intval($_GET['page']), 20, $_GET['fbc']));
$smarty->assign("feedback_pages", $feedback->getFeedbackPages(intval($_GET['page']), 20, $_GET['fbc']));

// feedback ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// НАСТРОЙКИ ///////// НАЧАЛО  ///////////////////////////////////////////////////////////////////
if ($_GET['get'] == 'settings') {

    if($_POST['save_feedback_is_send_admin_settings']){
        if($_POST['is_send_admin']==true) $is_active =1;
        else $is_active = 0;
        $elems = array(
            "set_value" => $is_active,
            "set_datetime_edit" => 'NOW()',
            "set_author" => USER_ID
        );
        $condition = array(
            "set_name" => "feedback_is_send_admin"
        );
        if ($feedback->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
        else $smarty->assign("error_message", 'Настройки не обновлены');
    }

    if($_POST['save_feedback_email_settings']){
        $search = array("'", '"', ";", ")");
        $replace = array('&quot;', '&quot;', '', '');
        $_POST['cnv_value'] = str_replace($search, $replace, $_POST['cnv_value']);
        $elems = array(
            "set_value" => $_POST['cnv_value'],
            "set_datetime_edit" => 'NOW()',
            "set_author" => USER_ID
        );
        $condition = array(
            "set_name" => "feedback_email_admin"
        );
        if ($feedback->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
        else $smarty->assign("error_message", 'Настройки не обновлены');
    }

    if($_POST['save_feedback_count_settings']){
        $_POST['cnv_value'] = intval($_POST['cnv_value']);
        $elems = array(
            "set_value" => $_POST['cnv_value'],
            "set_datetime_edit" => 'NOW()',
            "set_author" => USER_ID
        );
        $condition = array(
            "set_name" => "feedback_count_message"
        );
        if ($feedback->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
        else $smarty->assign("error_message", 'Настройки не обновлены');
    }

    $smarty->assign("feedback_settings_list", $feedback->getFeedbackSettings());
}
// НАСТРОЙКИ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// LETTERS ///////// НАЧАЛО  ///////////////////////////////////////////////////////////////////
if ($_GET['get'] == 'letters') {

    if($_POST['save_letter']){
        if ($feedback->saveLetter()) $smarty->assign("ok_message", 'Шаблон письма обновлен');
        else $smarty->assign("error_message", 'Шаблон письма не обновлен');
    }

    if($_POST['save_letter_title']){
        if ($feedback->saveLetterT()) $smarty->assign("ok_message", 'Шаблон письма обновлен');
        else $smarty->assign("error_message", 'Шаблон письма не обновлен');
    }

    $smarty->assign("letter", $feedback->getLetter($_GET['l_item']));
    $smarty->assign("letter_t", $feedback->getLetterT($_GET['l_item']));
}
// LETTERS ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////
?>