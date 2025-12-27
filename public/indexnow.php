<?php
/**
 * IndexNow API для rugger.info
 * Мгновенное уведомление Bing и Yandex о новых/обновленных страницах
 *
 * Использование:
 * - При публикации новости: indexnow.php?url=https://rugger.info/news/12345
 * - Пакетная отправка: indexnow.php?batch=1 (отправляет последние 100 URL)
 *
 * Документация: https://www.indexnow.org/documentation
 */

define('LANG', 'rus');
define('S_LANG', 'ru');

include_once('classes/config.php');
include_once('classes/DB.php');

$db = database::getInstance();

// Конфигурация IndexNow
$config = [
    'host' => 'rugger.info',
    'key' => '7a1c91d1f3aef672d92db9f5f41974fa',
    'keyLocation' => 'https://rugger.info/7a1c91d1f3aef672d92db9f5f41974fa.txt',
    'endpoints' => [
        'https://api.indexnow.org/indexnow',
        'https://yandex.com/indexnow'
    ]
];

header('Content-Type: application/json; charset=utf-8');

/**
 * Отправляет URL в IndexNow
 */
function submitToIndexNow($urls, $config) {
    $results = [];

    // Подготовка данных
    $data = [
        'host' => $config['host'],
        'key' => $config['key'],
        'keyLocation' => $config['keyLocation'],
        'urlList' => is_array($urls) ? $urls : [$urls]
    ];

    $jsonData = json_encode($data);

    foreach ($config['endpoints'] as $endpoint) {
        $ch = curl_init($endpoint);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($jsonData)
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => true
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        $results[$endpoint] = [
            'status' => $httpCode,
            'success' => in_array($httpCode, [200, 202]),
            'response' => $response,
            'error' => $error ?: null
        ];
    }

    return $results;
}

/**
 * Получает последние опубликованные URL
 */
function getRecentUrls($db, $limit = 100) {
    $urls = [];
    $sitepath = 'https://rugger.info/';

    // Последние новости
    $news = $db->selectElem(DB_T_PREFIX."news",
        "n_id, n_date_show",
        "n_is_active = 'yes' AND n_title_ru != '' AND n_date_show < NOW()
         ORDER BY n_date_show DESC LIMIT " . intval($limit)
    );

    if ($news) {
        foreach ($news as $item) {
            $urls[] = $sitepath . 'news/' . $item['n_id'];
        }
    }

    // Последние посты блога
    $posts = $db->selectElem(DB_T_PREFIX."blog_posts",
        "bp_address, bp_date_show",
        "bp_is_active = 'yes' AND bp_title_ru != '' AND bp_date_show < NOW()
         ORDER BY bp_date_show DESC LIMIT 20"
    );

    if ($posts) {
        foreach ($posts as $item) {
            $urls[] = $sitepath . 'blog/' . $item['bp_address'];
        }
    }

    return array_slice($urls, 0, $limit);
}

// Обработка запросов
$response = ['success' => false, 'message' => ''];

// Проверка API ключа (опционально - для защиты)
$apiKey = $_GET['api_key'] ?? $_POST['api_key'] ?? '';
$internalKey = 'rugger_indexnow_2025'; // Изменить на свой ключ

// Режим работы
if (isset($_GET['url'])) {
    // Одиночный URL
    $url = filter_var($_GET['url'], FILTER_VALIDATE_URL);

    if ($url && strpos($url, 'https://rugger.info') === 0) {
        $results = submitToIndexNow($url, $config);
        $response = [
            'success' => true,
            'message' => 'URL submitted to IndexNow',
            'url' => $url,
            'results' => $results
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Invalid URL. Must start with https://rugger.info'
        ];
    }

} elseif (isset($_GET['batch'])) {
    // Пакетная отправка последних URL
    $limit = min(intval($_GET['limit'] ?? 100), 10000);
    $urls = getRecentUrls($db, $limit);

    if (!empty($urls)) {
        $results = submitToIndexNow($urls, $config);
        $response = [
            'success' => true,
            'message' => 'Batch submitted to IndexNow',
            'url_count' => count($urls),
            'results' => $results
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No URLs found to submit'
        ];
    }

} elseif (isset($_POST['urls']) && is_array($_POST['urls'])) {
    // POST с массивом URL
    $urls = array_filter($_POST['urls'], function($url) {
        return filter_var($url, FILTER_VALIDATE_URL) &&
               strpos($url, 'https://rugger.info') === 0;
    });

    if (!empty($urls)) {
        $results = submitToIndexNow(array_values($urls), $config);
        $response = [
            'success' => true,
            'message' => 'URLs submitted to IndexNow',
            'url_count' => count($urls),
            'results' => $results
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No valid URLs provided'
        ];
    }

} else {
    // Справка
    $response = [
        'success' => true,
        'message' => 'IndexNow API for rugger.info',
        'usage' => [
            'single_url' => 'indexnow.php?url=https://rugger.info/news/12345',
            'batch' => 'indexnow.php?batch=1&limit=100',
            'post' => 'POST indexnow.php with urls[] array'
        ],
        'documentation' => 'https://www.indexnow.org/documentation'
    ];
}

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
