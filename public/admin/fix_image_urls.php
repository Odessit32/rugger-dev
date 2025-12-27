<?php
/**
 * Скрипт для исправления относительных путей к изображениям в новостях
 *
 * Проблема: TinyMCE сохранял пути как относительные (../upload/photos/...)
 * что приводило к неработающим картинкам на фронтенде.
 *
 * Решение: Конвертируем все относительные пути в абсолютные (/upload/photos/...)
 *
 * Запуск: php fix_image_urls.php [--dry-run]
 *   --dry-run - только показать что будет изменено, без реальных изменений
 */

// Увеличиваем лимит памяти
ini_set('memory_limit', '512M');

// Защита от запуска через браузер без авторизации
if (php_sapi_name() !== 'cli') {
    // Проверяем авторизацию в админке
    session_start();
    if (empty($_SESSION['login_name'])) {
        die('Access denied. Please login to admin panel first.');
    }
}

// Определяем путь к директории скрипта
$scriptDir = dirname(__FILE__);
chdir($scriptDir);

include_once($scriptDir . '/../classes/config.php');
include_once($scriptDir . '/../classes/DB.php');

$dryRun = in_array('--dry-run', $argv ?? []) || isset($_GET['dry-run']);

echo "=== Исправление путей к изображениям в новостях ===\n\n";

if ($dryRun) {
    echo "*** РЕЖИМ ПРОСМОТРА (dry-run) - изменения НЕ будут сохранены ***\n\n";
}

$db = database::getInstance();
if (!$db) {
    die("Ошибка подключения к БД\n");
}

// Паттерны для поиска и замены
$patterns = [
    // ../upload/photos/ -> /upload/photos/
    '/(src\s*=\s*["\'])\.\.\/upload\/photos\//i' => '$1/upload/photos/',
    // ../../upload/photos/ -> /upload/photos/
    '/(src\s*=\s*["\'])\.\.\/\.\.\/upload\/photos\//i' => '$1/upload/photos/',
    // ../../../upload/photos/ -> /upload/photos/
    '/(src\s*=\s*["\'])\.\.\/\.\.\/\.\.\/upload\/photos\//i' => '$1/upload/photos/',
    // upload/photos/ (без слэша в начале) -> /upload/photos/
    '/(src\s*=\s*["\'])upload\/photos\//i' => '$1/upload/photos/',
    // То же самое для href
    '/(href\s*=\s*["\'])\.\.\/upload\/photos\//i' => '$1/upload/photos/',
    '/(href\s*=\s*["\'])\.\.\/\.\.\/upload\/photos\//i' => '$1/upload/photos/',
    '/(href\s*=\s*["\'])\.\.\/\.\.\/\.\.\/upload\/photos\//i' => '$1/upload/photos/',
    '/(href\s*=\s*["\'])upload\/photos\//i' => '$1/upload/photos/',
];

// Поля для обработки
$fields = ['n_text_ru', 'n_text_ua', 'n_text_en', 'n_description_ru', 'n_description_ua', 'n_description_en'];

// Получаем количество новостей
$countResult = $db->selectElem(DB_T_PREFIX . "news", "COUNT(*) as cnt", "1");
$totalNews = $countResult[0]['cnt'] ?? 0;

echo "Найдено новостей: $totalNews\n\n";

$updatedCount = 0;
$fixedImagesCount = 0;
$batchSize = 500;
$offset = 0;

// Обрабатываем батчами
while ($offset < $totalNews) {
    $news = $db->selectElem(
        DB_T_PREFIX . "news",
        "n_id, " . implode(", ", $fields),
        "1 ORDER BY n_id ASC LIMIT $offset, $batchSize"
    );

    if (!$news) {
        break;
    }

    foreach ($news as $item) {
        $needUpdate = false;
        $updates = [];
        $newsChanges = [];

        foreach ($fields as $field) {
            if (empty($item[$field])) {
                continue;
            }

            $original = $item[$field];
            $fixed = $original;

            foreach ($patterns as $pattern => $replacement) {
                $count = 0;
                $fixed = preg_replace($pattern, $replacement, $fixed, -1, $count);
                if ($count > 0) {
                    $fixedImagesCount += $count;
                    $newsChanges[] = "  - $field: исправлено $count путей";
                }
            }

            if ($fixed !== $original) {
                $needUpdate = true;
                $updates[$field] = $fixed;
            }
        }

        if ($needUpdate) {
            $updatedCount++;
            echo "Новость ID {$item['n_id']}:\n";
            echo implode("\n", $newsChanges) . "\n";

            if (!$dryRun) {
                // Выполняем обновление
                $condition = ["n_id" => $item['n_id']];
                if ($db->updateElem(DB_T_PREFIX . "news", $updates, $condition)) {
                    echo "  [OK] Сохранено\n";
                } else {
                    echo "  [ERROR] Ошибка сохранения!\n";
                }
            }
            echo "\n";
        }
    }

    $offset += $batchSize;
    echo "Обработано: $offset / $totalNews\n";

    // Освобождаем память
    unset($news);
}

echo "\n=== Результаты ===\n";
echo "Всего новостей: $totalNews\n";
echo "Новостей с исправлениями: $updatedCount\n";
echo "Всего исправлено путей: $fixedImagesCount\n";

if ($dryRun && $updatedCount > 0) {
    echo "\n*** Для применения изменений запустите без --dry-run ***\n";
    if (php_sapi_name() !== 'cli') {
        echo "\nИли перейдите по ссылке: <a href='fix_image_urls.php'>fix_image_urls.php</a>\n";
    }
}

echo "\nГотово!\n";
