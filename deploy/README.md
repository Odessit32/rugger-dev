# Деплой Rugger.info

## Структура папки deploy/

```
deploy/
├── deploy.sh           # Основной скрипт деплоя
├── rollback.sh         # Скрипт отката
├── sql_migrations.sql  # SQL миграции для phpMyAdmin
└── README.md           # Эта инструкция
```

## Перед первым использованием

1. Откройте `deploy.sh` и `rollback.sh`
2. Заполните секцию КОНФИГУРАЦИЯ:
   ```bash
   REMOTE_USER="your_ssh_user"      # SSH пользователь
   REMOTE_HOST="rugger.info"        # Хост сервера
   REMOTE_PATH="/path/to/public_html"  # Путь к сайту
   DB_USER="your_db_user"           # Пользователь БД
   DB_NAME="your_db_name"           # Имя базы данных
   ```

3. Сделайте скрипты исполняемыми:
   ```bash
   chmod +x deploy.sh rollback.sh
   ```

## Порядок деплоя

### 1. Деплой файлов

```bash
cd /Users/platika/Desktop/Rugger/deploy
./deploy.sh
```

Скрипт автоматически:
- Создаст бэкап БД и файлов на проде
- Создаст архив локальных изменений
- Загрузит и применит изменения
- Очистит кэш
- Проверит доступность сайта

### 2. SQL миграции (вручную в phpMyAdmin)

После деплоя файлов выполните SQL из `sql_migrations.sql`:

1. Откройте phpMyAdmin
2. Выберите базу данных rugger
3. Перейдите в SQL
4. Выполняйте блоки последовательно

**Порядок выполнения:**
1. Блок 1: Новые поля
2. Блоки 2-7: Индексы
3. Блок 8: Анализ таблиц
4. Блок 9: Оптимизация (опционально, в период низкой нагрузки)

### 3. Проверка после деплоя

- [ ] Главная страница https://rugger.info
- [ ] Страница новости
- [ ] Страница матча
- [ ] Страница персоны
- [ ] Страница клуба
- [ ] Поиск
- [ ] Админка
- [ ] Мета-теги (View Source)

## Откат

При проблемах после деплоя:

```bash
./rollback.sh
```

Скрипт покажет доступные бэкапы и предложит выбрать точку восстановления.

## Бэкапы

Бэкапы хранятся на сервере в `~/backups/`:
- `db_YYYYMMDD_HHMMSS.sql` - бэкап БД
- `site_YYYYMMDD_HHMMSS.tar.gz` - бэкап файлов
- `files_diff_YYYYMMDD_HHMMSS/` - изменённые файлы (для точечного отката)

Рекомендуется периодически очищать старые бэкапы:
```bash
ssh user@rugger.info "find ~/backups -mtime +30 -delete"
```

## Troubleshooting

### Ошибка "Permission denied"
```bash
# Проверьте права на файлы
ssh user@rugger.info "ls -la /path/to/public_html"
```

### Ошибка "Duplicate key name"
Индекс уже существует - можно пропустить этот ALTER TABLE.

### Сайт не работает после деплоя
1. Проверьте логи: `tail -f /var/log/apache2/error.log`
2. Запустите откат: `./rollback.sh`

### Медленные SQL запросы после индексов
Выполните ANALYZE TABLE для обновления статистики.
