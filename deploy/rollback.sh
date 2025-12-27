#!/bin/bash
# =====================================================
# Rugger.info Rollback Script
# Откат к предыдущей версии из бэкапа
# =====================================================

set -e

# =====================================================
# КОНФИГУРАЦИЯ - ДОЛЖНА СОВПАДАТЬ С deploy.sh
# =====================================================
REMOTE_USER="your_ssh_user"
REMOTE_HOST="rugger.info"
REMOTE_PATH="/path/to/public_html"
REMOTE_BACKUP_DIR="~/backups"

DB_USER="your_db_user"
DB_NAME="your_db_name"
# =====================================================

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${RED}=================================================${NC}"
echo -e "${RED}   Rugger.info ROLLBACK Script${NC}"
echo -e "${RED}   ВНИМАНИЕ: Это откатит изменения!${NC}"
echo -e "${RED}=================================================${NC}"
echo ""

# Проверка конфигурации
if [[ "$REMOTE_USER" == "your_ssh_user" ]]; then
    echo -e "${RED}ОШИБКА: Заполните конфигурацию в начале скрипта!${NC}"
    exit 1
fi

# Показываем доступные бэкапы
echo -e "${YELLOW}Доступные бэкапы:${NC}"
ssh ${REMOTE_USER}@${REMOTE_HOST} "ls -lht ${REMOTE_BACKUP_DIR}/ | head -20"

echo ""
read -p "Введите timestamp бэкапа для отката (например: 20251225_143000): " BACKUP_TIMESTAMP

if [ -z "$BACKUP_TIMESTAMP" ]; then
    echo -e "${RED}Timestamp не указан. Отмена.${NC}"
    exit 1
fi

# Проверяем существование бэкапов
echo -e "${YELLOW}Проверяю наличие бэкапов...${NC}"
ssh ${REMOTE_USER}@${REMOTE_HOST} << ENDSSH
    if [ ! -f "${REMOTE_BACKUP_DIR}/site_${BACKUP_TIMESTAMP}.tar.gz" ]; then
        echo "ОШИБКА: Бэкап файлов не найден!"
        exit 1
    fi
    if [ ! -f "${REMOTE_BACKUP_DIR}/db_${BACKUP_TIMESTAMP}.sql" ]; then
        echo "ОШИБКА: Бэкап БД не найден!"
        exit 1
    fi
    echo "Бэкапы найдены"
ENDSSH

if [ $? -ne 0 ]; then
    echo -e "${RED}Бэкапы не найдены. Отмена.${NC}"
    exit 1
fi

echo ""
echo -e "${RED}ВЫ УВЕРЕНЫ? Это действие:${NC}"
echo "  - Восстановит файлы из site_${BACKUP_TIMESTAMP}.tar.gz"
echo "  - Восстановит БД из db_${BACKUP_TIMESTAMP}.sql"
echo ""
read -p "Продолжить откат? [yes/NO]: " confirm

if [ "$confirm" != "yes" ]; then
    echo "Откат отменён."
    exit 0
fi

# =====================================================
# Откат файлов
# =====================================================
echo -e "${YELLOW}Откатываю файлы...${NC}"

ssh ${REMOTE_USER}@${REMOTE_HOST} << ENDSSH
    # Создаём бэкап текущего состояния перед откатом
    ROLLBACK_TS=\$(date +%Y%m%d_%H%M%S)
    echo "Создаю бэкап текущего состояния перед откатом..."
    tar -czf ${REMOTE_BACKUP_DIR}/pre_rollback_\${ROLLBACK_TS}.tar.gz ${REMOTE_PATH}

    echo "Восстанавливаю файлы..."
    tar -xzf ${REMOTE_BACKUP_DIR}/site_${BACKUP_TIMESTAMP}.tar.gz -C /

    # Очистка кэша
    rm -rf ${REMOTE_PATH}/templates_c/* 2>/dev/null || true
    rm -rf ${REMOTE_PATH}/cache/* 2>/dev/null || true

    echo "Файлы восстановлены"
ENDSSH

echo -e "${GREEN}✓ Файлы восстановлены${NC}"

# =====================================================
# Откат БД
# =====================================================
echo ""
read -p "Откатить также базу данных? [y/N]: " rollback_db

if [[ "$rollback_db" =~ ^[Yy]$ ]]; then
    echo -e "${YELLOW}Откатываю базу данных...${NC}"

    ssh ${REMOTE_USER}@${REMOTE_HOST} << ENDSSH
        # Бэкап текущей БД перед откатом
        ROLLBACK_TS=\$(date +%Y%m%d_%H%M%S)
        mysqldump -u ${DB_USER} -p --single-transaction ${DB_NAME} > ${REMOTE_BACKUP_DIR}/pre_rollback_db_\${ROLLBACK_TS}.sql

        echo "Восстанавливаю БД..."
        mysql -u ${DB_USER} -p ${DB_NAME} < ${REMOTE_BACKUP_DIR}/db_${BACKUP_TIMESTAMP}.sql

        echo "БД восстановлена"
ENDSSH

    echo -e "${GREEN}✓ База данных восстановлена${NC}"
else
    echo "БД не откатывается"
fi

# =====================================================
# Проверка
# =====================================================
echo ""
echo -e "${YELLOW}Проверяю работоспособность...${NC}"

HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" https://rugger.info/)

if [ "$HTTP_CODE" == "200" ]; then
    echo -e "${GREEN}✓ Сайт отвечает (HTTP $HTTP_CODE)${NC}"
else
    echo -e "${RED}⚠ Сайт вернул HTTP $HTTP_CODE - проверьте вручную!${NC}"
fi

echo ""
echo -e "${GREEN}=================================================${NC}"
echo -e "${GREEN}   Откат завершён${NC}"
echo -e "${GREEN}=================================================${NC}"

exit 0
