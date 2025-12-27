#!/bin/bash
# =====================================================
# Rugger.info Deploy Script with Expect
# =====================================================

set -e

REMOTE_USER="kredoo3g_web"
REMOTE_HOST="rugger.info"
REMOTE_PASS='&nm3F*aux4Pq'
DB_USER="kredoo3g_rugger"
DB_PASS='F8CPAkx%'
DB_NAME="kredoo3g_rugger"

LOCAL_PROJECT_DIR="/Users/platika/Desktop/Rugger"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
ARCHIVE="/tmp/rugger_deploy_${TIMESTAMP}.tar.gz"

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}=================================================${NC}"
echo -e "${BLUE}   Rugger.info Deploy${NC}"
echo -e "${BLUE}   $(date)${NC}"
echo -e "${BLUE}=================================================${NC}"
echo ""

# ШАГ 1: Создаём архив
echo -e "${YELLOW}ШАГ 1: Создаю архив...${NC}"
cd ${LOCAL_PROJECT_DIR}
tar -czf ${ARCHIVE} public/
echo -e "${GREEN}✓ Архив создан: ${ARCHIVE}${NC}"
echo ""

# ШАГ 2: Бэкап на сервере и загрузка
echo -e "${YELLOW}ШАГ 2: Создаю бэкап на сервере и загружаю файлы...${NC}"

/usr/bin/expect << 'EXPECT_SCRIPT'
set timeout 300
set host "rugger.info"
set user "kredoo3g_web"
set pass {&nm3F*aux4Pq}
set db_user "kredoo3g_rugger"
set db_pass {F8CPAkx%}
set db_name "kredoo3g_rugger"
set timestamp [clock format [clock seconds] -format "%Y%m%d_%H%M%S"]
set archive "/tmp/rugger_deploy_[clock format [clock seconds] -format "%Y%m%d"]*.tar.gz"

# Получаем реальное имя архива
set archive_file [lindex [glob -nocomplain /tmp/rugger_deploy_*.tar.gz] end]

puts "Подключаюсь к серверу..."

# Загрузка файла через SCP
spawn scp -o StrictHostKeyChecking=no $archive_file ${user}@${host}:~/rugger_update.tar.gz
expect {
    "password:" { send "$pass\r" }
    "yes/no" { send "yes\r"; exp_continue }
}
expect eof

puts "Файл загружен, выполняю команды на сервере..."

# SSH для выполнения команд
spawn ssh -o StrictHostKeyChecking=no ${user}@${host}
expect {
    "password:" { send "$pass\r" }
    "yes/no" { send "yes\r"; exp_continue }
}

expect "$ "
send "echo '=== Создаю бэкап ===' && mkdir -p ~/backups\r"
expect "$ "

# Бэкап БД
send "mysqldump -u $db_user -p'$db_pass' --single-transaction $db_name > ~/backups/db_$timestamp.sql 2>&1 && echo 'DB backup OK' || echo 'DB backup FAILED'\r"
expect "$ "

# Бэкап файлов
send "tar -czf ~/backups/site_$timestamp.tar.gz ~/rugger.info/public_html 2>&1 && echo 'Files backup OK' || echo 'Files backup FAILED'\r"
expect "$ "

# Распаковка и применение
send "echo '=== Применяю обновления ===' && mkdir -p ~/update_temp && tar -xzf ~/rugger_update.tar.gz -C ~/update_temp\r"
expect "$ "

send "cp -r ~/update_temp/public/* ~/rugger.info/public_html/ 2>&1 && echo 'Files copied OK'\r"
expect "$ "

# Очистка кэша
send "rm -rf ~/rugger.info/public_html/templates_c/* 2>/dev/null; rm -rf ~/rugger.info/public_html/cache/* 2>/dev/null; echo 'Cache cleared'\r"
expect "$ "

# Очистка временных файлов
send "rm -rf ~/update_temp ~/rugger_update.tar.gz && echo 'Cleanup done'\r"
expect "$ "

# Показываем бэкапы
send "ls -lh ~/backups/ | tail -5\r"
expect "$ "

send "exit\r"
expect eof

puts "\n=== Деплой завершён ==="
EXPECT_SCRIPT

echo ""
echo -e "${GREEN}✓ Деплой выполнен${NC}"
echo ""

# Проверка сайта
echo -e "${YELLOW}ШАГ 3: Проверяю работоспособность...${NC}"
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" https://rugger.info/)
if [ "$HTTP_CODE" == "200" ]; then
    echo -e "${GREEN}✓ Сайт отвечает (HTTP $HTTP_CODE)${NC}"
else
    echo -e "${RED}⚠ Сайт вернул HTTP $HTTP_CODE - проверьте вручную!${NC}"
fi

echo ""
echo -e "${BLUE}=================================================${NC}"
echo -e "${YELLOW}ВАЖНО: Выполните SQL миграции в phpMyAdmin!${NC}"
echo -e "${BLUE}=================================================${NC}"
echo "Файл: deploy/sql_migrations.sql"
echo ""

# Очистка
rm -f ${ARCHIVE}

exit 0
