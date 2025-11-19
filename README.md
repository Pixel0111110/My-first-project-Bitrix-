# My First Bitrix Project

## Как устанавливал Bitrix

1. **Создал Docker-окружение** с nginx, PHP 8.1 и MySQL 8.0 через docker-compose.yml
2. **Скачал дистрибутив Bitrix**
3. **Распаковал архив**
4. **Запустил установщик** по адресу http://localhost/bitrixsetup.php
5. **Настроил базу данных**
6. **Завершил установку** через веб-интерфейс установщика

## Как запустить проект

```bash
# Запуск Docker-окружения
docker-compose up -d

# Остановка окружения
docker-compose down

# Просмотр статуса контейнеров
docker-compose ps

# Просмотр логов
docker-compose logs
После запуска открыть в браузере: http://localhost
```

## Команды Git которые использовал

```bash
# Инициализация репозитория
git init

# Добавление файлов в индекс
git add .

# Создание коммита (с использованием nano как редактора)
git commit

# Настройка удаленного репозитория
git remote add origin https://github.com/Pixel0111110/My-first-project-Bitrix-.git

# Переименование основной ветки
git branch -M main

# Отправка кода на GitHub
git push -u origin main

# Просмотр истории коммитов
git log --oneline

# Настройка nano как редактора по умолчанию
git config --global core.editor "nano"
```

## Файл .gitignore включает:

1. Ядро Bitrix (/bitrix/)
2. Кэш-файлы и логи
3. Файлы окружения (.env)
4. Системные файлы
