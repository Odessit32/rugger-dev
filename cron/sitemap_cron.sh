#!/bin/bash
#
# Cron script для автоматической генерации sitemap
# Запускать ежедневно или по необходимости
#
# Примеры настройки cron:
# Каждый день в 3:00 ночи:
#   0 3 * * * /path/to/sitemap_cron.sh >> /var/log/sitemap_cron.log 2>&1
#
# Каждые 6 часов:
#   0 */6 * * * /path/to/sitemap_cron.sh >> /var/log/sitemap_cron.log 2>&1
#

# Директория скрипта
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"

echo "========================================="
echo "Sitemap Generation Started: $(date)"
echo "========================================="

# Для Docker окружения
if command -v docker-compose &> /dev/null; then
    cd "$PROJECT_DIR"
    docker-compose exec -T php php /var/www/html/public/sitemap.php
elif command -v docker &> /dev/null; then
    # Если используется docker без compose
    CONTAINER_ID=$(docker ps -q -f name=rugger-php)
    if [ -n "$CONTAINER_ID" ]; then
        docker exec "$CONTAINER_ID" php /var/www/html/public/sitemap.php
    else
        echo "Error: PHP container not found"
        exit 1
    fi
else
    # Для прямого запуска на сервере без Docker
    cd "$PROJECT_DIR/public"
    php sitemap.php
fi

echo ""
echo "========================================="
echo "Sitemap Generation Finished: $(date)"
echo "========================================="
