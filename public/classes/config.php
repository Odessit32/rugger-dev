<?php
/**
 * Minimal config for local/Docker environment.
 * Production credentials are in config.prod.php (NEVER overwrite on server!)
 */

// 1) PRODUCTION CONFIG - load first if exists (contains DB credentials)
// This file should NEVER be overwritten during deploy!
$prodConfig = __DIR__ . '/config.prod.php';
if (file_exists($prodConfig)) {
    include_once $prodConfig;
    // Don't return - continue to set other defaults with if(!defined()) checks
}

// 2) Optional: include host-specific overrides if they exist (ignored in git)
$hostSpecific = __DIR__ . '/config_' . php_uname('n') . '.php';
if (file_exists($hostSpecific)) {
    include_once $hostSpecific;
}

// 3) Environment (only for local/docker)
if (!defined('ENV')) {
    define('ENV', getenv('ENV') ?: 'LOCAL_DOCKER');
}

// 3) Base URL (use current Host header: localhost:8080 inside browser)
$httpHost = $_SERVER['HTTP_HOST'] ?? 'localhost:8080';

// For legacy code that expects SERVER without scheme:
if (!defined('SERVER')) {
    define('SERVER', $_SERVER['HTTP_HOST'] ?? 'localhost:8080');
}

// Site path (usually "/")
if (!defined('SITEPATH')) {
    define('SITEPATH', '/');
}

// 4) Caching flags
// Set IS_CACHING=true and IS_CACHING_QUERY=true in production for better performance
// For Docker/local: use environment variables or keep defaults
if (!defined('IS_CACHING')) {
    $cachingEnabled = getenv('IS_CACHING');
    define('IS_CACHING', $cachingEnabled === 'true' || $cachingEnabled === '1');
}
if (!defined('IS_CACHING_QUERY')) {
    // Enable query caching in production (requires memcache/redis)
    $queryCachingEnabled = getenv('IS_CACHING_QUERY');
    define('IS_CACHING_QUERY', $queryCachingEnabled === 'true' || $queryCachingEnabled === '1');
}
if (!defined('CACHING_TYPE')) {
    define('CACHING_TYPE', 0);
}
if (!defined('CACHING_LIFETIME')) {
    // Cache lifetime in seconds (default 5 minutes for production)
    define('CACHING_LIFETIME', (int)(getenv('CACHING_LIFETIME') ?: 300));
}

// 5) Smarty
if (!defined('SMARTYLIBPATH')) {
    define('SMARTYLIBPATH', 'libs_3.1.27');
}

if (!defined('TEMPLATE_DIR')) {
    define('TEMPLATE_DIR', './templates/');
}
if (!defined('COMPILE_DIR')) {
    define('COMPILE_DIR', './templates_c/');
}
if (!defined('CACHE_DIR')) {
    define('CACHE_DIR', './cache/');
}

// 6) Database (from environment variables) - only if not already defined by config.prod.php
if (!defined('DB_SERVER')) {
    $dbHost = getenv('DB_HOST') ?: 'db';
    $dbUser = getenv('DB_USER') ?: 'app';
    $dbPass = getenv('DB_PASS') ?: 'app';
    $dbName = getenv('DB_NAME') ?: 'app';

    define('DB_SERVER', $dbHost);
    define('DB_SERVER_USERNAME', $dbUser);
    define('DB_SERVER_PASSWORD', $dbPass);
    define('DB_DATABASE', $dbName);
}

// 7) DB prefix + language defaults
if (!defined('DB_T_PREFIX')) {
    define('DB_T_PREFIX', 'rgr_');
}

if (!defined('D_LANG')) {
    define('D_LANG', 'rus');
}
if (!defined('D_S_LANG')) {
    define('D_S_LANG', 'ru');
}
if (!defined('D_HTML_LANG')) {
    define('D_HTML_LANG', 'ru_RU');
}

// 8) Timezone
if (function_exists('date_default_timezone_set')) {
    date_default_timezone_set('Europe/Moscow');
    ini_set('date.timezone', 'Europe/Moscow');
}