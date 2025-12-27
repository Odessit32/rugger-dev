<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('../classes/admin/admin_announce.php');

$announce = new announce;

// АНАНСЫ ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////
if($_POST['add_new_announce']){
    if ($announce->createAnnounce($_POST)) $smarty->assign("ok_message", 'Анонс добавлен');
    else $smarty->assign("error_message", 'Анонс не добавлен');
}

if($_POST['save_announce_changes']){
    if ($announce->updateAnnounce($_POST)) $smarty->assign("ok_message", 'Анонс обновлен');
    else $smarty->assign("error_message", 'Анонс не обновлен');
}

if($_POST['delete_announce']){
    if ($announce->deleteAnnounce($_POST['an_id'])) header('Location: ./?show=announce');
    else $smarty->assign("error_message", 'Анонс не удален');
}

if ($_GET['get']=='edit' and $_GET['item']>0){
    $announce_item = $announce->getAnnounceItem($_GET['item']);
    $smarty->assign("announce_item", $announce_item);
}

$smarty->assign("announce_list", $announce->getAnnounceList(intval($_GET['page']), 20, $_GET['anc']));
$smarty->assign("announce_pages", $announce->getAnnouncePages(intval($_GET['page']), 20, $_GET['anc']));
$smarty->assign("announce_teams", $announce->getAnnounceTeams());

// АНАНСЫ ///////// КОНЕЦ  ///////////////////////////////////////////////////////////////////

// НАСТРОЙКИ ///////// НАЧАЛО /////////////////////////////////////////////////////////////////
if($_POST['save_announce_is_informer_settings']){
    if($_POST['is_active']==true) $is_active =1;
    else $is_active = 0;
    $elems = array(
        "set_value" => $is_active,
        "set_datetime_edit" => 'NOW()',
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => "announce_is_informer"
    );
    if ($announce->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
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
        if ($conf->saveVarTitle($elems, $condition)) $ok_message .= "Переменная сайта обновлена ($lang)\n";
        else $error_message .= "Переменная сайта не обновлен ($lang)\n";
    }

    if($_POST['save_announce_championship']){
        $elems = array(
            "set_value" => intval($_POST['ch_id']),
            "set_datetime_edit" => 'NOW()',
            "set_author" => USER_ID
        );
        $condition = array(
            "set_name" => "announce_championship"
        );
        if ($announce->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
        else $smarty->assign("error_message", 'Настройки не обновлены');
    }

    $smarty->assign("championship_list", $announce->getChampionshipList());
    $smarty->assign("conf_vars", $conf->getConfVars());
    $smarty->assign("announce_settings_list", $announce->getAnnounceSettings());
}



// НАСТРОЙКИ ///////// КОНЕЦ /////////////////////////////////////////////////////////////////

?>