#!/bin/bash
# =====================================================
# Rugger.info Deploy Script
# Безопасный деплой с автоматическим бэкапом
# =====================================================

set -e  # Останавливаемся при любой ошибке

# =====================================================
# КОНФИГУРАЦИЯ - ЗАПОЛНИТЕ ПЕРЕД ИСПОЛЬЗОВАНИЕМ!
# =====================================================
REMOTE_USER="kredoo3g_web"
REMOTE_HOST="rugger.info"
REMOTE_PATH="~/rugger.info/public_html"
REMOTE_BACKUP_DIR="~/backups"

DB_USER="kredoo3g_rugger"
DB_NAME="kredoo3g_rugger"

LOCAL_PROJECT_DIR="/Users/platika/Desktop/Rugger"
# =====================================================

# Цвета для вывода
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

TIMESTAMP=$(date +%Y%m%d_%H%M%S)

echo -e "${BLUE}=================================================${NC}"
echo -e "${BLUE}   Rugger.info Deploy Script${NC}"
echo -e "${BLUE}   $(date)${NC}"
echo -e "${BLUE}=================================================${NC}"
echo ""

# Проверка конфигурации
if [[ "$REMOTE_USER" == "your_ssh_user" ]]; then
    echo -e "${RED}ОШИБКА: Заполните конфигурацию в начале скрипта!${NC}"
    exit 1
fi

# =====================================================
# Функция подтверждения
# =====================================================
confirm() {
    read -p "$1 [y/N]: " response
    case "$response" in
        [yY][eE][sS]|[yY])
            return 0
            ;;
        *)
            return 1
            ;;
    esac
}

# =====================================================
# ШАГ 1: Создание бэкапа на проде
# =====================================================
echo -e "${YELLOW}ШАГ 1: Создание бэкапа на продакшене...${NC}"

ssh ${REMOTE_USER}@${REMOTE_HOST} << ENDSSH
    mkdir -p ${REMOTE_BACKUP_DIR}

    echo "Создаю бэкап базы данных..."
    mysqldump -u ${DB_USER} -p --single-transaction --routines --triggers ${DB_NAME} > ${REMOTE_BACKUP_DIR}/db_${TIMESTAMP}.sql

    echo "Создаю бэкап файлов..."
    tar -czf ${REMOTE_BACKUP_DIR}/site_${TIMESTAMP}.tar.gz ${REMOTE_PATH}

    echo "Бэкапы созданы:"
    ls -lh ${REMOTE_BACKUP_DIR}/*${TIMESTAMP}*
ENDSSH

if [ $? -ne 0 ]; then
    echo -e "${RED}ОШИБКА при создании бэкапа! Деплой отменён.${NC}"
    exit 1
fi

echo -e "${GREEN}✓ Бэкап успешно создан${NC}"
echo ""

# =====================================================
# ШАГ 2: Подготовка локального архива
# =====================================================
echo -e "${YELLOW}ШАГ 2: Подготовка архива для загрузки...${NC}"

cd ${LOCAL_PROJECT_DIR}
tar -czf /tmp/rugger_update_${TIMESTAMP}.tar.gz public/

echo -e "${GREEN}✓ Архив создан: /tmp/rugger_update_${TIMESTAMP}.tar.gz${NC}"
echo ""

# =====================================================
# ШАГ 3: Загрузка на сервер
# =====================================================
echo -e "${YELLOW}ШАГ 3: Загрузка файлов на сервер...${NC}"

scp /tmp/rugger_update_${TIMESTAMP}.tar.gz ${REMOTE_USER}@${REMOTE_HOST}:~/

echo -e "${GREEN}✓ Файлы загружены${NC}"
echo ""

# =====================================================
# ШАГ 4: Распаковка и применение изменений
# =====================================================
echo -e "${YELLOW}ШАГ 4: Применение изменений...${NC}"

ssh ${REMOTE_USER}@${REMOTE_HOST} << ENDSSH
    mkdir -p ~/update_temp
    tar -xzf ~/rugger_update_${TIMESTAMP}.tar.gz -C ~/update_temp

    # Копируем с сохранением прав
    rsync -av --backup --backup-dir=${REMOTE_BACKUP_DIR}/files_diff_${TIMESTAMP} \
        ~/update_temp/public/ ${REMOTE_PATH}/

    # Очистка кэша Smarty
    rm -rf ${REMOTE_PATH}/templates_c/* 2>/dev/null || true
    rm -rf ${REMOTE_PATH}/cache/* 2>/dev/null || true

    # Очистка временных файлов
    rm -rf ~/update_temp
    rm ~/rugger_update_${TIMESTAMP}.tar.gz

    echo "Файлы обновлены"
ENDSSH

echo -e "${GREEN}✓ Изменения применены${NC}"
echo ""

# =====================================================
# ШАГ 5: Проверка работоспособности
# =====================================================
echo -e "${YELLOW}ШАГ 5: Проверка работоспособности...${NC}"

HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" https://rugger.info/)

if [ "$HTTP_CODE" == "200" ]; then
    echo -e "${GREEN}✓ Сайт отвечает (HTTP $HTTP_CODE)${NC}"
else
    echo -e "${RED}⚠ Сайт вернул HTTP $HTTP_CODE${NC}"
    if confirm "Откатить изменения?"; then
        echo "Откатываю..."
        ssh ${REMOTE_USER}@${REMOTE_HOST} << ENDSSH
            tar -xzf ${REMOTE_BACKUP_DIR}/site_${TIMESTAMP}.tar.gz -C /
            echo "Файлы восстановлены из бэкапа"
ENDSSH
        echo -e "${GREEN}Откат выполнен${NC}"
        exit 1
    fi
fi

echo ""

# =====================================================
# НАПОМИНАНИЕ О МИГРАЦИЯХ БД
# =====================================================
echo -e "${BLUE}=================================================${NC}"
echo -e "${YELLOW}ВАЖНО: Не забудьте выполнить SQL миграции!${NC}"
echo -e "${BLUE}=================================================${NC}"
echo ""
echo "Выполните в phpMyAdmin следующие запросы:"
echo ""
echo "1. Добавление поля related_news:"
echo "   ALTER TABLE news ADD COLUMN n_related_news TEXT NULL DEFAULT NULL"
echo "   COMMENT 'JSON массив ID связанных новостей' AFTER n_top;"
echo ""
echo "2. Оптимизация индексов - см. файл db_optimize.sql"
echo ""
echo -e "${BLUE}=================================================${NC}"
echo -e "${GREEN}Деплой файлов завершён успешно!${NC}"
echo -e "${BLUE}=================================================${NC}"
echo ""
echo "Бэкапы сохранены в: ${REMOTE_BACKUP_DIR}/"
echo "  - db_${TIMESTAMP}.sql"
echo "  - site_${TIMESTAMP}.tar.gz"
echo ""

# Очистка локального архива
rm /tmp/rugger_update_${TIMESTAMP}.tar.gz

exit 0
