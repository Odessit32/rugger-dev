<?php
/**
 * Designed for illi.CMS
 * http://illi.od.ua/
 */

// Suppress PHP 8.0 warnings for legacy code in production
error_reporting(E_ALL & ~E_WARNING & ~E_DEPRECATED);
ini_set('display_errors', '0');

include_once('../classes/config.php');
include_once('../classes/DB.php');
include_once('../classes/admin/admin_login.php');
include_once('../libs/Smarty.class.php');

$db_connection = false;

$smarty = new Smarty();
$smarty->template_dir="../templates/admin";
$smarty->compile_dir="../templates_c";
$smarty->config_dir="../configs";
$smarty->cache_dir="../cache";

// Register custom modifiers for PHP 8.0 compatibility
$smarty->registerPlugin('modifier', 'print_r', 'smarty_modifier_print_r');
$smarty->registerPlugin('modifier', 'gettype', 'smarty_modifier_gettype');

function smarty_modifier_print_r($value) {
    return '<pre>' . htmlspecialchars(print_r($value, true)) . '</pre>';
}

function smarty_modifier_gettype($value) {
    return gettype($value);
}
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
$admin_user = array();
$login = new login;

// language
if (!defined('LANG')) {
    define('LANG', D_LANG);
}
if (!defined('S_LANG')) {
    define('S_LANG', D_S_LANG);
}

// title and text in different languages
include_once('../classes/language/lang_'.D_S_LANG.'.php');
$smarty->assign("language", $language);

// подпапка в которой лежит сайт
$scheme_protocol = 'http://';
if (isset($_SERVER['HTTPS']) &&
    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
    $scheme_protocol = 'https://';
}
$sitepath = SERVER."/";
$sitepath .= (SITEPATH != '/') ? SITEPATH.'/' : '' ;
$smarty->assign("sitepath", $scheme_protocol.$sitepath);
$smarty->assign("site_path", $sitepath);

if(isset($_GET['logout']) and isset($_SESSION['login_name'])){
    if($_GET['logout']==1){
        $login->getDelSession();
        session_destroy();
        header('Location: '.$scheme_protocol.$sitepath."admin/");
    }
}

if(isset($_POST['login'])){
    if (!$login->login($_POST['login_name'], $_POST['password'])) $smarty->assign("error_message", 'Ошибка авторизации');
}

//var_dump($_SESSION);
if(isset($_SESSION['login_name'])){
    $smarty->assign("session_login", $_SESSION['login_name']);
    $login_info = $login->getLoginSession();
    $admin_user = array(
        "login_id" => USER_ID,
        "login_name" => USER_NAME,
        "admin_status" => USER_IS_ADMIN,
        "publisher_status" => USER_IS_PUBLISHER
    );
    //--------------------------- PROFILE ---------------------------------
    if(!empty($_GET['show']) && $_GET['show']=='profile') include_once('../classes/admin/inc_admin_login.php');
    $smarty->assign("login_id", USER_ID);
    $smarty->assign("login_name", USER_NAME);
    $smarty->assign("admin_status", USER_IS_ADMIN);
    $smarty->assign("publisher_status", USER_IS_PUBLISHER);

    if (empty($_GET['show'])) $_GET['show'] = 'main';

    // проверка прав пользователя
    if (count($_GET)>0 and $_GET['show'] != 'profile' and USER_IS_ADMIN == 'no' and USER_IS_PUBLISHER == 'no')
        if (!$login->checkAdminRights() and USER_IS_ADMIN != 'yes') header('Location: '.$sitepath."admin");

    // настройки языков
    if ($_GET['show'] != 'main') {
        include_once('../classes/admin/admin_main.php');
        $main = new main;
        $smarty->assign("main_settings", $main->getMainSettings());
    }

    //--------------------------- MENU ------------------------------------
    include_once('../classes/admin/inc_admin_menu.php');

    //--------------------------- PAGES -----------------------------------
    if($_GET['show']=='pages') include_once('../classes/admin/inc_admin_page.php');

    //--------------------------- ADMINS ----------------------------------
    if($_GET['show']=='admins') include_once('../classes/admin/inc_admin_admin.php');

    //--------------------------- MAIN ------------------------------------
    if($_GET['show']=='main') include_once('../classes/admin/inc_admin_main.php');

    //--------------------------- NEWS ------------------------------------
    if($_GET['show']=='news') include_once('../classes/admin/inc_admin_news.php');

    //--------------------------- FILES -----------------------------------
    if($_GET['show']=='files') include_once('../classes/admin/inc_admin_files.php');

    //--------------------------- PHOTOS -----------------------------------
    if($_GET['show']=='photos') include_once('../classes/admin/inc_admin_photos.php');

    //--------------------------- VIDEOS -----------------------------------
    if($_GET['show']=='videos') include_once('../classes/admin/inc_admin_videos.php');

    //--------------------------- BANNERS -----------------------------------
    if($_GET['show']=='banners') include_once('../classes/admin/inc_admin_banners.php');

    //--------------------------- INFORMERS -----------------------------------
    if($_GET['show']=='informers') include_once('../classes/admin/inc_admin_informers.php');

    //--------------------------- STAFF -----------------------------------
    if($_GET['show']=='staff') include_once('../classes/admin/inc_admin_staff.php');

    //--------------------------- TEAM -----------------------------------
    if($_GET['show']=='team') include_once('../classes/admin/inc_admin_team.php');

    //--------------------------- CLUB -----------------------------------
    if($_GET['show']=='club') include_once('../classes/admin/inc_admin_club.php');

    //--------------------------- CHAMPIONSHIP -----------------------------------
    if($_GET['show']=='championship') include_once('../classes/admin/inc_admin_championship.php');

    //--------------------------- COMPETITIONS -----------------------------------
    if($_GET['show']=='competitions') include_once('../classes/admin/inc_admin_competitions.php');

    //--------------------------- GAMES -----------------------------------
    if($_GET['show']=='games') include_once('../classes/admin/inc_admin_games.php');

    //--------------------------- ANNOUNCE -----------------------------------
    //if($_GET['show']=='announce') include_once('../classes/admin/inc_admin_announce.php');

    //--------------------------- FEEDBACK -----------------------------------
    //if($_GET['show']=='feedback') include_once('../classes/admin/inc_admin_feedback.php');

    //--------------------------- DB_BACKUP -----------------------------------
    if($_GET['show']=='db_backup') include_once('../classes/admin/inc_admin_db_backup.php');

    //--------------------------- VOTES -----------------------------------
    //if($_GET['show']=='votes') include_once('../classes/admin/inc_admin_votes.php');

    //--------------------------- LIVE -----------------------------------
    if($_GET['show']=='live') include_once('../classes/admin/inc_admin_live.php');

    //--------------------------- REDIRECTS -----------------------------------
    if($_GET['show']=='redirects') include_once('../classes/admin/inc_admin_redirects.php');

    //--------------------------- BLOG -----------------------------------
    if($_GET['show']=='blog') include_once('../classes/admin/inc_admin_blog.php');

    //--------------------------- NOTIFICATIONS -----------------------------------
    if($_GET['show']=='notifications') include_once('../classes/admin/inc_admin_notifications.php');

} else $smarty->assign("session_login", false);

$smarty->display("admin_index.tpl");
?>
