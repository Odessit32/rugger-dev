<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('display_startup_errors', 1);

// Suppress PHP 8.0 warnings for legacy code
error_reporting(E_ALL & ~E_DEPRECATED);
if (!defined('ENV')) {
    define('ENV', getenv('ENV') ?: 'local');
} /* удалить при заливке на прод*/

$db_connection = false;
$cached_key = false;
$total_db_query = array(
    'count' => 0,
    'data_select' => array(),
    'data_incert' => array(),
    'data_update' => array(),
    'data_delete' => array(),
    'data_other' => array(),
    'connections' => 0,
    'disconnections' => 0
);
function microtime_float() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
$time_start_ = microtime_float();
$admin_user = array();

// config and connection to DB
include_once('classes/config.php');
include_once('classes/DB.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Smarty

include_once(__DIR__.'/libs/Smarty.class.php');
$smarty = new Smarty();
$smarty->setTemplateDir(TEMPLATE_DIR);
$smarty->setCompileDir(COMPILE_DIR);
//$smarty->config_dir=array('./configs/');
$smarty->setCacheDir(CACHE_DIR);
$smarty->error_reporting = E_ALL & ~E_DEPRECATED;

// Register modifiers for PHP 8.0+ compatibility
$smarty->registerPlugin('modifier', 'strtolower', 'strtolower');
$smarty->registerPlugin('modifier', 'strtoupper', 'strtoupper');
$smarty->registerPlugin('modifier', 'count', 'count');
$smarty->registerPlugin('modifier', 'nl2br', 'nl2br');
// Smarty END

$smarty->assign("ENV", ENV);

// is admin
if(!empty($_SESSION['login_name'])){

    include_once('classes/admin/admin_login.php');
    $login = new login;
    $smarty->assign("session_login", $_SESSION['login_name']);
    $login_info = $login->getLoginSession();
    $admin_user = array(
        "login_id" => USER_ID,
        "login_name" => USER_NAME,
        "admin_status" => USER_IS_ADMIN,
        "publisher_status" => USER_IS_PUBLISHER
    );
    if (!empty($login_info)){
        $smarty->assign("admin_user", $admin_user);
    }
    if (!defined('IS_CACHING_U')) {
        define('IS_CACHING_U', false);
    }
} else {
    $smarty->assign("session_login", false);
}
if (!defined('IS_CACHING_U')) {
    if (defined('IS_CACHING')) {
        define('IS_CACHING_U', IS_CACHING);
    } else {
        define('IS_CACHING_U', false);
    }
}
// is admin end
// caching
if (IS_CACHING_U) {
    $smarty->caching = true;
    $smarty->cache_lifetime = CACHING_LIFETIME;
    $smarty->setCacheLifetime(CACHING_LIFETIME);
    if (rand(1, 10) > 9) { // clear old cached templates, but don't load this work for every client
        $smarty->clearAllCache(CACHING_LIFETIME);
    }
    include_once('classes/caching.php');
    $caching = new caching();
    $cached_key = $caching->getRequestKey();
    if (!empty($cached_key)) {
        $cached_template = $caching->getCahedTemplate($cached_key);
        if (!empty($cached_template)) {
            if ($smarty->isCached($cached_template, $cached_key)) {
                $smarty->loadFilter('output', 'trimwhitespace');
                $smarty->display($cached_template, $cached_key);
                $time_end_ = microtime_float();
                $time = $time_end_ - $time_start_;
                echo '<!-- c time: ' . $time . ' -->';
                exit();
            }
        }
    }
}
// Classes
include_once('classes/url.php');
include_once('classes/client_base.php');

// to remake connection to the database (once => + global handle)

// site address
$scheme_protocol = 'http://';
if (isset($_SERVER['HTTPS']) &&
    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
    $scheme_protocol = 'https://';
}
$sitepath = SERVER."/";
$sitepath .= (SITEPATH != '/') ? SITEPATH.'/' : '' ;
$smarty->assign("sitepath_lang", $scheme_protocol.$sitepath);
$imagepath = $scheme_protocol.$sitepath;
$smarty->assign("imagepath", $imagepath);
$smarty->assign("site_path", $sitepath);

// parsing addresses
$url = new url;
$requestUri = isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : '';
$url->getURL($requestUri);

// address with the language
if ($url->lang == D_LANG) $smarty->assign("sitepath", $scheme_protocol.$sitepath);
else $smarty->assign("sitepath", $scheme_protocol.$sitepath.$url->lang."/");

if (!empty($url->section['page'])) {
    $section_address = $url->section['page']['page_path'].$url->section['page']['p_adress'].'/';
} else {
    $section_address = '';
}

$smarty->assign("section_address", $section_address);

// language
if (!defined('LANG')) {
    define('LANG', $url->lang);
}
if (!defined('S_LANG')) {
    define('S_LANG', $url->sLang);
}

// notifications
include_once(__DIR__.'/classes/notifications.php');
$notification = new Notifications;
$smarty->assign("notifications_list", $notification->getList());

// configuration variables from the database
include_once(__DIR__.'/classes/conf_vars.php');
$conf = new conf_vars;

// title and text in different languages
include_once('classes/language/lang_'.S_LANG.'.php');
$smarty->assign("language", $language);

// default section variable
$section_type = (!empty($url->section['info']))?$url->section['info']['pe_item_type']:'';
$smarty->assign("section_type", $section_type);
$section_type_id = (!empty($url->section['info']))?$url->section['info']['pe_item_id']:'';
$smarty->assign("section_type_id", $section_type_id);
$smarty->assign("section", $url->section);
$smarty->assign("section_country", (!empty($url->menu_sections[100]))?$url->menu_sections[100]:false);
$smarty->assign("section_championship", (!empty($url->menu_sections[101]))?$url->getExtraParamsMenuCompetitions($url->menu_sections[101]):false);
$smarty->assign("is_section_home", $url->isSectionHome);

//echo "<pre>";
//var_dump($section_type);
//var_dump($section_type_id);
//echo "\n";
//var_dump($url->section['page']['page_path'].$url->section['page']['p_adress']);
//var_dump($url->menu_sections);
//echo "</pre>";

// the variables in the template
$conf_settings = $conf->conf_settings;
$smarty->assign("dlang", D_LANG);
$smarty->assign("lang", $url->lang);
$smarty->assign("sLang", $url->sLang);
$smarty->assign("htmlLang", $url->htmlLang);
$conf_vars = $conf->getConfVars();
$smarty->assign("conf_vars", $conf->getConfVars());
$month = $conf->getMonth($url->lang);
$smarty->assign("month", $month);
$month_i = $conf->getMonthI($url->lang);
$smarty->assign("month_i", $month_i);
$wday = $conf->getWDay($url->lang);
$smarty->assign("wday", $wday);
$wday_l = $conf->getWDayL($url->lang);
$smarty->assign("wday_l", $wday_l);
$lang_settings = $conf->getLandSettings();
$smarty->assign("lang_settings", $lang_settings['list']);
$smarty->assign("lang_switch", $lang_settings['lang_switch']);
$smarty->assign("menu_main", $url->getMainMenu());
//	$smarty->assign("menu_futer", $url->getFuterMenu());
$smarty->assign("menu_tree", $url->menu);
$smarty->assign("is_submenu", $url->is_submenu);
$smarty->assign("pages", $url->pages);
$smarty->assign("page_type", $url->type);
$smarty->assign("page", $url->page);
//	$smarty->assign("array_menu", $url->array_menu);
$smarty->assign("isSection", $url->isSection);

if ($url->type == 'main') include_once(__DIR__.'/classes/inc_main.php');
if ($url->type == 'page') {
    include_once(__DIR__.'/classes/tools.php');
    $tools = new Tools;
    $smarty->assign("page_item", $tools->prepareData($url->page, 'page'));
    include_once('classes/'.$url->module['mod_class']);
    if (IS_CACHING_U && !in_array($url->module['mod_template'], array('feedback.tpl', 'search.tpl'))) {
        $caching->setCachedTemplate($cached_key, $url->module['mod_template']);
        $smarty->loadFilter('output', 'trimwhitespace');
        $smarty->display($url->module['mod_template'], $cached_key);
    } else {
        $smarty->caching = false;
        $smarty->cache_lifetime = 0;
        $smarty->setCacheLifetime(0);
        $smarty->loadFilter('output', 'trimwhitespace');
        $smarty->display($url->module['mod_template']);
    }
}
// если не найдена страница
if ($url->type == '404') {
    header("HTTP/1.0 404 Not Found");
    if (IS_CACHING_U) {
        $caching->setCachedTemplate($cached_key, "404.tpl");
        $smarty->loadFilter('output', 'trimwhitespace');
        $smarty->display("404.tpl", $cached_key);
    } else {
        $smarty->loadFilter('output', 'trimwhitespace');
        $smarty->display("404.tpl");
    }
}

$time_end_ = microtime_float();
$time = $time_end_ - $time_start_;
echo '<!-- time: '.$time.' -->';
echo '<!-- '.$total_db_query['count'].' -->';
//    echo "<pre>";
//    var_dump($total_db_query);
//    echo "</pre>";
if (!empty($admin_user) && (!empty($admin_user['admin_status']) || !empty($admin_user['publisher_status']))){
}