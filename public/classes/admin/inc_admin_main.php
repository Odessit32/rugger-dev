<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */
include_once('../classes/admin/admin_page.php');
include_once('../classes/admin/admin_main.php');
include_once('../classes/admin/admin_conf_vars.php');
$page = new page;
$main = new main;
$conf = new conf_vars;

$smarty->assign("pages_parent", $page->getPagesList());

// meta SEO functions start
$meta_seo_item = 1;
$meta_seo_item_type = 'main';
include_once('../classes/admin/inc_admin_meta_seo.php');
// meta SEO functions finish

// НАСТРОЙКИ ///////// НАЧАЛО ///////////////////////////////////////////////////////////////////////////////////
if(!empty($_POST['save_lang_settings'])){
    // русский
    if ($_POST['rus_is_active']) $rus_is_active = 'yes';
    else $rus_is_active = 'no';
    $elems = array(
        "sl_is_active" => $rus_is_active,
        "sl_datetime_edit" => 'NOW()',
        "sl_author" => USER_ID
    );
    $condition = array(
        "sl_title" => 'rus'
    );
    if ($main->updateMainSettings($elems, $condition)) $ok_message .= "Настройки языка обновлены (русский)\n";
    else $error_message .= "Настройки языка не обновлены (русский)\n";
    // украинский
    if ($_POST['ukr_is_active']) $ukr_is_active = 'yes';
    else $ukr_is_active = 'no';
    $elems = array(
        "sl_is_active" => $ukr_is_active,
        "sl_datetime_edit" => 'NOW()',
        "sl_author" => USER_ID
    );
    $condition = array(
        "sl_title" => 'ukr'
    );
    if ($main->updateMainSettings($elems, $condition)) $ok_message .= "Настройки языка обновлены (украинский)\n";
    else $error_message .= "Настройки языка не обновлены (украинский)\n";
    // английский
    if ($_POST['eng_is_active']) $eng_is_active = 'yes';
    else $eng_is_active = 'no';
    $elems = array(
        "sl_is_active" => $eng_is_active,
        "sl_datetime_edit" => 'NOW()',
        "sl_author" => USER_ID
    );
    $condition = array(
        "sl_title" => 'eng'
    );
    if ($main->updateMainSettings($elems, $condition)) $ok_message .= "Настройки языка обновлены (английский)\n";
    else $error_message .= "Настройки языка не обновлены (английский)\n";
    $smarty->assign("error_message", nl2br($error_message));
    $smarty->assign("ok_message", nl2br($ok_message));
}

if (!empty($_POST['save_var'])){
    $search = array("\\"."'", "\\".'"', "'", '"');
    $replace = array('', '', '', '', '', '');
    $_POST['cnv_value'] = (strlen(trim(html_entity_decode(strip_tags($_POST['cnv_value']))))>0) ? str_replace($search, $replace, $_POST['cnv_value']) : '';

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

if(!empty($_POST['save_settings_var'])){
    $_POST['set_value'] = intval($_POST['set_value']);
    $search = array("'", '"');
    $replace = array('', '');
    $elems = array(
        "set_value" => $_POST['set_value'],
        "set_datetime_edit" => 'NOW()',
        "set_author" => USER_ID
    );
    $condition = array(
        "set_name" => str_replace($search, $replace, $_POST['set_name'])
    );
    if ($main->saveSettings($elems, $condition)) $smarty->assign("ok_message", 'Настройки обновлены');
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

$cache_vars = array(
    'caching_time' => CACHING_LIFETIME,
    'is_caching' => IS_CACHING,
    'caching_type' => CACHING_TYPE,
);

if (!empty($_POST['caching_time_save'])){
    $_POST['caching_time_value'] = intval($_POST['caching_time_value']);

    if ($main->ConfigUpdateVar('CACHING_LIFETIME', $_POST['caching_time_value'])) {
        $smarty->assign("ok_message", 'Настройки обновлены');
        $cache_vars['caching_time'] = $_POST['caching_time_value'];
    }
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if (!empty($_POST['is_caching_save'])){
    $_POST['is_caching_value'] = ($_POST['is_caching_value'])?true:false;

    if ($main->ConfigUpdateVar('IS_CACHING', $_POST['is_caching_value'])) {
        if (!empty($_POST['is_caching_value'])) {
            include_once('../classes/caching.php');

            $caching = new caching();
            $caching->clearCache();
        }
        $smarty->assign("ok_message", 'Настройки обновлены');
        $cache_vars['is_caching'] = $_POST['is_caching_value'];
    }
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if (!empty($_POST['caching_type_save'])){
    $_POST['caching_type_value'] = ($_POST['caching_type_value'])?true:false;

    if ($main->ConfigUpdateVar('CACHING_TYPE', $_POST['caching_type_value'])) {
        $smarty->assign("ok_message", 'Настройки обновлены');
        $cache_vars['caching_type'] = $_POST['caching_type_value'];
    }
    else $smarty->assign("error_message", 'Настройки не обновлены');
}

if (!empty($_POST['caching_clear_function'])){
    include_once('../classes/caching.php');

    $caching = new caching();
    $caching->clearCache();
}

$smarty->assign("cache_vars", $cache_vars);
$smarty->assign("conf_vars", $conf->getConfVars());
$smarty->assign("main_settings", $main->getMainSettings());
$smarty->assign("srttings_list", $main->getSettings());

// НАСТРОЙКИ ///////// КОНЕЦ ////////////////////////////////////////////////////////////////////////////////////

// MAIN BANNER INFORMER ///////////////////////////////////////////////////////////////////////////////////////////////////////

if(!empty($_POST['add_main_informer'])){ // прикрепление информера к main странице
    if ($main->addMainBannerInformer()) $smarty->assign("ok_message", 'Информер прикреплен');
    else $smarty->assign("error_message", 'Информер не прикреплен');
}

if(!empty($_POST['add_main_banner'])){ // прикрепление баннера к main странице
    if ($main->addMainBannerInformer()) $smarty->assign("ok_message", 'Баннер прикреплен');
    else $smarty->assign("error_message", 'Баннер не прикреплен');
}

if(!empty($_POST['delete_mbi'])){ // удаление баннера / информера с main страницы
    if ($main->deleteMainBannerInformer($_POST['mbi_id'])) $smarty->assign("ok_message", 'Удаление прошло успешно');
    else $smarty->assign("error_message", 'Удалить не удалось');
}

if(!empty($_POST['save_mbi'])){ // сохранение изменений баннера / информера на main странице
    if ($main->saveMainBannerInformer($_POST['mbi_id'])) $smarty->assign("ok_message", 'Изменения сохранены успешно');
    else $smarty->assign("error_message", 'Изменения сохранить не удалось');
}

$smarty->assign("mbi", $main->getBanInfList());
$smarty->assign("main_ban_inf_list", $main->getMainBanInfList());

// MAIN BANNER INFORMER ///////////////////////////////////////////////////////////////////////////////////////////////////////