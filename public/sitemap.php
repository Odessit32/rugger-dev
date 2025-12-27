<?php
/**
 * Sitemap Generator for rugger.info
 * Updated: 2025 - Single language version
 *
 * Standards:
 * - https://www.sitemaps.org/protocol.html
 * - https://developers.google.com/search/docs/crawling-indexing/sitemaps/build-sitemap
 */

// Увеличиваем лимиты
ini_set('memory_limit', '1G');
set_time_limit(600);

define('LANG', 'rus');
define('S_LANG', 'ru');

include_once('classes/config.php');
include_once('classes/DB.php');
include_once('classes/conf_vars.php');
$conf = new conf_vars;

$db = database::getInstance();

// Базовый URL сайта (только русская версия)
$sitepath = 'https://rugger.info';

date_default_timezone_set('Europe/Kiev');
header("content-type: text/plain; charset=utf-8");

// Переходим в директорию скрипта
chdir(dirname(__FILE__));

// XML заголовки
$header = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$header .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
$footer = "</urlset>\n";

// Удаляем старые файлы
$oldSitemaps = glob('./sitemap_*.xml');
foreach ($oldSitemaps as $oldFile) {
	@unlink($oldFile);
}

// Счётчики
$fileNum = 1;
$urlCount = 0;
$totalUrls = 0;
$maxUrlsPerFile = 45000;

// Открываем первый файл
$currentFile = fopen("./sitemap_{$fileNum}.xml", "w");
fwrite($currentFile, $header);

/**
 * Записывает URL в текущий файл
 */
function writeUrl($loc, $changefreq, $priority, $lastmod = '') {
	global $currentFile, $fileNum, $urlCount, $totalUrls, $maxUrlsPerFile, $header, $footer;

	$xml = "<url>\n";
	$xml .= "  <loc>" . htmlspecialchars($loc, ENT_XML1, 'UTF-8') . "</loc>\n";
	if ($lastmod) {
		$xml .= "  <lastmod>" . $lastmod . "</lastmod>\n";
	}
	$xml .= "  <changefreq>" . $changefreq . "</changefreq>\n";
	$xml .= "  <priority>" . $priority . "</priority>\n";
	$xml .= "</url>\n";

	fwrite($currentFile, $xml);
	$urlCount++;
	$totalUrls++;

	if ($urlCount >= $maxUrlsPerFile) {
		fwrite($currentFile, $footer);
		fclose($currentFile);

		$fileNum++;
		$urlCount = 0;
		$currentFile = fopen("./sitemap_{$fileNum}.xml", "w");
		fwrite($currentFile, $header);
	}
}

echo "Starting sitemap generation (single language)...\n";

// Главная страница
writeUrl($sitepath, 'daily', '1.0', date('Y-m-d'));
echo "Homepage added\n";

// Собираем модули
$modules = [];

// СТРАНИЦЫ (рекурсивно) — только русская версия
function processPages($parent_id = 0, $path = '') {
	global $db, $modules, $sitepath;

	$parent_id = intval($parent_id);
	$pages = $db->selectElem(DB_T_PREFIX."pages",
		"p_id, p_parent_id, p_mod_id, p_adress, p_title_ru",
		"p_parent_id = '$parent_id' AND p_is_delete = 'no' AND p_is_active = 'yes' AND p_title_ru != '' ORDER BY p_order DESC"
	);

	if ($pages) {
		foreach ($pages as $item) {
			$pagePath = $path . $item['p_adress'];
			$changefreq = ($item['p_mod_id'] == 1) ? 'daily' : 'weekly';
			$priority = ($parent_id == 0) ? '0.9' : '0.8';

			writeUrl($sitepath . '/' . $pagePath, $changefreq, $priority);

			if ($item['p_mod_id'] > 0) {
				$modules[] = [
					'p_id' => $item['p_id'],
					'mod_id' => $item['p_mod_id'],
					'path' => $pagePath . '/'
				];
			}

			processPages($item['p_id'], $pagePath . '/');
		}
	}
}

processPages(0, '');
echo "Pages processed. Modules found: " . count($modules) . "\n";

// МОДУЛИ
$news_processed = false;
$photo_processed = false;
$video_processed = false;

foreach ($modules as $item) {
	// НОВОСТИ
	if ($item['mod_id'] == 1 && !$news_processed) {
		echo "Processing news...\n";

		// Отдельные новости (порциями)
		$offset = 0;
		$limit = 1000;

		do {
			$temp = $db->selectElem(DB_T_PREFIX."news",
				"n_id, n_date_show",
				"n_is_active = 'yes' AND n_title_ru != '' AND n_date_show < NOW() ORDER BY n_date_show DESC LIMIT $offset, $limit"
			);

			if ($temp) {
				foreach ($temp as $n_item) {
					$lastmod = date("Y-m-d", strtotime($n_item['n_date_show']));
					writeUrl($sitepath . '/' . $item['path'] . $n_item['n_id'], 'monthly', '0.7', $lastmod);
				}
			}

			$offset += $limit;
		} while ($temp && count($temp) == $limit);

		echo "News items processed\n";

		// Категории новостей
		$temp = $db->selectElem(DB_T_PREFIX."news_categories",
			"nc_id, nc_address",
			"nc_is_active = 'yes' ORDER BY nc_order DESC"
		);

		if ($temp) {
			foreach ($temp as $n_item) {
				writeUrl($sitepath . '/' . $item['path'] . $n_item['nc_address'], 'daily', '0.8');

				// Пагинация категорий
				$temp_ncp = $db->selectElem(DB_T_PREFIX."news", "COUNT(*) as C_N",
					"n_is_active = 'yes' AND n_title_ru != '' AND n_date_show < NOW() AND n_nc_id = '".(int)$n_item['nc_id']."'"
				);
				if ($temp_ncp && $temp_ncp[0]['C_N'] > 0) {
					$c_pages = ceil($temp_ncp[0]['C_N'] / $conf->conf_settings['count_news_page']);
					for ($i = 1; $i < $c_pages; $i++) {
						writeUrl($sitepath . '/' . $item['path'] . $n_item['nc_address'] . '/page' . $i, 'daily', '0.6');
					}
				}
			}
		}

		// Пагинация общего списка
		$temp_ncp = $db->selectElem(DB_T_PREFIX."news", "COUNT(*) as C_N",
			"n_is_active = 'yes' AND n_title_ru != '' AND n_date_show < NOW()"
		);
		if ($temp_ncp && $temp_ncp[0]['C_N'] > 0) {
			$c_pages = ceil($temp_ncp[0]['C_N'] / $conf->conf_settings['count_news_page']);
			for ($i = 1; $i < $c_pages; $i++) {
				writeUrl($sitepath . '/' . $item['path'] . 'page' . $i, 'daily', '0.6');
			}
		}

		$news_processed = true;
		echo "News categories and pagination processed\n";
	}

	// ФОТОГАЛЕРЕЯ
	elseif ($item['mod_id'] == 2 && !$photo_processed) {
		echo "Processing photo gallery...\n";

		$offset = 0;
		$limit = 1000;

		do {
			$temp = $db->selectElem(DB_T_PREFIX."photo_gallery",
				"phg_id",
				"phg_is_active = 'yes' ORDER BY phg_id DESC LIMIT $offset, $limit"
			);

			if ($temp) {
				foreach ($temp as $g_item) {
					writeUrl($sitepath . '/' . $item['path'] . $g_item['phg_id'], 'monthly', '0.7');
				}
			}

			$offset += $limit;
		} while ($temp && count($temp) == $limit);

		// Категории
		$temp = $db->selectElem(DB_T_PREFIX."photo_categories",
			"phc_id, phc_address",
			"phc_is_active = 'yes' ORDER BY phc_order DESC"
		);

		if ($temp) {
			foreach ($temp as $phc_item) {
				writeUrl($sitepath . '/' . $item['path'] . $phc_item['phc_address'], 'weekly', '0.8');

				$temp_ncp = $db->selectElem(DB_T_PREFIX."photo_gallery", "COUNT(*) as C_N",
					"phg_is_active = 'yes' AND phg_phc_id = '".(int)$phc_item['phc_id']."'"
				);
				if ($temp_ncp && $temp_ncp[0]['C_N'] > 0) {
					$c_pages = ceil($temp_ncp[0]['C_N'] / $conf->conf_settings['count_photo_page']);
					for ($i = 1; $i < $c_pages; $i++) {
						writeUrl($sitepath . '/' . $item['path'] . $phc_item['phc_address'] . '/page' . $i, 'weekly', '0.6');
					}
				}
			}
		}

		// Общая пагинация
		$temp_ncp = $db->selectElem(DB_T_PREFIX."photo_gallery", "COUNT(*) as C_N", "phg_is_active = 'yes'");
		if ($temp_ncp && $temp_ncp[0]['C_N'] > 0) {
			$c_pages = ceil($temp_ncp[0]['C_N'] / $conf->conf_settings['count_photo_page']);
			for ($i = 1; $i < $c_pages; $i++) {
				writeUrl($sitepath . '/' . $item['path'] . 'page' . $i, 'monthly', '0.5');
			}
		}

		$photo_processed = true;
		echo "Photo gallery processed\n";
	}

	// ВИДЕОГАЛЕРЕЯ
	elseif ($item['mod_id'] == 3 && !$video_processed) {
		echo "Processing video gallery...\n";

		$offset = 0;
		$limit = 1000;

		do {
			$temp = $db->selectElem(DB_T_PREFIX."video_gallery",
				"vg_id",
				"vg_is_active = 'yes' ORDER BY vg_id DESC LIMIT $offset, $limit"
			);

			if ($temp) {
				foreach ($temp as $g_item) {
					writeUrl($sitepath . '/' . $item['path'] . $g_item['vg_id'], 'monthly', '0.7');
				}
			}

			$offset += $limit;
		} while ($temp && count($temp) == $limit);

		// Категории
		$temp = $db->selectElem(DB_T_PREFIX."video_categories",
			"vc_id, vc_address",
			"vc_is_active = 'yes' ORDER BY vc_order DESC"
		);

		if (!empty($temp)) {
			foreach ($temp as $vc_item) {
				writeUrl($sitepath . '/' . $item['path'] . $vc_item['vc_address'], 'weekly', '0.8');

				$temp_ncp = $db->selectElem(DB_T_PREFIX."video_gallery", "COUNT(*) as C_N",
					"vg_is_active = 'yes' AND vg_vc_id = '".(int)$vc_item['vc_id']."'"
				);
				if ($temp_ncp && $temp_ncp[0]['C_N'] > 0) {
					$c_pages = ceil($temp_ncp[0]['C_N'] / $conf->conf_settings['count_video_page']);
					for ($i = 1; $i < $c_pages; $i++) {
						writeUrl($sitepath . '/' . $item['path'] . $vc_item['vc_address'] . '/page' . $i, 'weekly', '0.6');
					}
				}
			}
		}

		// Общая пагинация
		$temp_ncp = $db->selectElem(DB_T_PREFIX."video_gallery", "COUNT(*) as C_N", "vg_is_active = 'yes'");
		if ($temp_ncp && $temp_ncp[0]['C_N'] > 0) {
			$c_pages = ceil($temp_ncp[0]['C_N'] / $conf->conf_settings['count_video_page']);
			for ($i = 1; $i < $c_pages; $i++) {
				writeUrl($sitepath . '/' . $item['path'] . 'page' . $i, 'monthly', '0.5');
			}
		}

		$video_processed = true;
		echo "Video gallery processed\n";
	}
}

// Закрываем последний файл
fwrite($currentFile, $footer);
fclose($currentFile);

// Создаём индексный файл
$currentDate = date('Y-m-d');
$index = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$index .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

for ($i = 1; $i <= $fileNum; $i++) {
	$index .= "  <sitemap>\n";
	$index .= "    <loc>" . htmlspecialchars($sitepath . "/sitemap_" . $i . ".xml", ENT_XML1, 'UTF-8') . "</loc>\n";
	$index .= "    <lastmod>" . $currentDate . "</lastmod>\n";
	$index .= "  </sitemap>\n";
}

$index .= '</sitemapindex>';
file_put_contents('./sitemap.xml', $index);

echo "\n=== Sitemap Generation Complete ===\n";
echo "Total URL entries: " . $totalUrls . "\n";
echo "Files generated: " . $fileNum . "\n";
echo "Index: " . $sitepath . "/sitemap.xml\n";
echo "Generated at: " . date('Y-m-d H:i:s') . "\n";

// === ПИНГ ПОИСКОВИКОВ ===
echo "\n=== Pinging Search Engines ===\n";

$sitemapUrl = urlencode($sitepath . '/sitemap.xml');
$pingUrls = [
    'Google' => "https://www.google.com/ping?sitemap={$sitemapUrl}",
    'Bing' => "https://www.bing.com/ping?sitemap={$sitemapUrl}",
];

foreach ($pingUrls as $engine => $pingUrl) {
    $ch = curl_init($pingUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_USERAGENT => 'Rugger.info Sitemap Generator'
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $status = ($httpCode == 200) ? 'OK' : "Error (HTTP {$httpCode})";
    echo "{$engine}: {$status}\n";
}

// === IndexNow для Bing и Yandex ===
echo "\n=== IndexNow Submission ===\n";

$indexNowKey = '7a1c91d1f3aef672d92db9f5f41974fa';
$indexNowEndpoints = [
    'IndexNow API' => 'https://api.indexnow.org/indexnow',
    'Yandex' => 'https://yandex.com/indexnow'
];

// Собираем URL последних новостей для IndexNow (до 100)
$recentUrls = [];
$recentNews = $db->selectElem(DB_T_PREFIX."news",
    "n_id",
    "n_is_active = 'yes' AND n_title_ru != '' AND n_date_show < NOW() AND n_date_show > DATE_SUB(NOW(), INTERVAL 7 DAY) ORDER BY n_date_show DESC LIMIT 100"
);

if ($recentNews) {
    foreach ($recentNews as $item) {
        $recentUrls[] = $sitepath . '/news/' . $item['n_id'];
    }
}

if (!empty($recentUrls)) {
    $indexNowData = json_encode([
        'host' => 'rugger.info',
        'key' => $indexNowKey,
        'keyLocation' => $sitepath . '/' . $indexNowKey . '.txt',
        'urlList' => $recentUrls
    ]);

    foreach ($indexNowEndpoints as $name => $endpoint) {
        $ch = curl_init($endpoint);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $indexNowData,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($indexNowData)
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => true
        ]);

        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $status = in_array($httpCode, [200, 202]) ? 'OK' : "Error (HTTP {$httpCode})";
        echo "{$name}: {$status} (" . count($recentUrls) . " URLs)\n";
    }
} else {
    echo "No recent URLs to submit to IndexNow\n";
}

echo "\n=== Done ===\n";
?>
