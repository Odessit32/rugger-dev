<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('../classes/admin/admin_votes.php');

$votes = new votes;

// ГОЛОСОВАНИЯ ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////
if($_POST['add_new_votes']){
    if ($votes->createVotes($_POST)) $smarty->assign("ok_message", 'Голосование добавлено');
    else $smarty->assign("error_message", 'Голосование не добавлено');
}

if($_POST['save_votes_changes']){
    if ($votes->updateVotes($_POST)) $smarty->assign("ok_message", 'Голосование обновлено');
    else $smarty->assign("error_message", 'Голосование не обновлено');
}

if($_POST['delete_votes']){
    if ($votes->deleteVotes($_POST['vt_id'])) header('Location: ./?show=votes');
    else $smarty->assign("error_message", 'Голосование не удалено');
}

if ($_GET['get']=='edit' and $_GET['item']>0){
    $votes_item = $votes->getVotesItem($_GET['item']);
    $smarty->assign("votes_item", $votes_item);
}

$smarty->assign("votes_list", $votes->getVotesList(intval($_GET['page']), 20, $_GET['sort']));
$smarty->assign("votes_pages", $votes->getVotesPages(intval($_GET['page']), 20, $_GET['sort']));

// ГОЛОСОВАНИЯ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// НАСТРОЙКИ ///////// НАЧАЛО /////////////////////////////////////////////////////////////////

if($_POST['save_votes_count_settings']){
    $_POST['cnv_value'] = intval($_POST['cnv_value']);
    $elems = array(
        "set_value" => $_POST['cnv_value'],
        "set_datetime_edit" => 'NOW()',
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "count_votes_page"
    );
    if ($votes->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if ($_GET['get'] == 'settings') {

    // Заголовки ////////////////////////////////////////////////////////////////
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
        if ($conf->saveVarTitle($elems, $condition)) $smarty->assign("ok_message", "Переменная сайта обновлена ($lang)");
        else $smarty->assign("error_message", "Переменная сайта не обновлен ($lang)\n");
    }

    $smarty->assign("conf_vars", $conf->getConfVars());
    $smarty->assign("votes_settings_list", $votes->getVotesSettings());
}
// НАСТРОЙКИ ///////// КОНЕЦ /////////////////////////////////////////////////////////////////

?>