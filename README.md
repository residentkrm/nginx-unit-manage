# Nginx Unit Management System

Система управления конфигурацией Nginx Unit через веб-интерфейс и API.

## Возможности

- [x] Управление приложениями (Applications)
- [x] Управление слушателями (Listeners)
- [x] Управление маршрутами (Routes)
- [x] Хранение конфигураций в базе данных
- [x] Поддержка Unix socket для Unit Control API
- [ ] Управление сертификатами (SSL/TLS)
- [ ] Настройка custom access log
- [ ] Настройка proxying

## Настройка

### 1. Настройка переменных окружения

В файле `.env` добавьте:

```env
UNIT_API_URL=unix:/opt/homebrew/var/run/unit/control.sock
# или для HTTP:
# UNIT_API_URL=http://127.0.0.1:8500
UNIT_API_TIMEOUT=10
UNIT_VERIFY_SSL=false
```

### 2. Запуск миграций

Миграции уже выполнены, но при необходимости:

```bash
php artisan migrate
```

- PHP 8.2+
- Laravel 12+
- Nginx Unit с включенным Control API
- MySQL/MariaDB

## Примечания

- Все изменения сохраняются в базе данных независимо от состояния Unit API
- Деплой конфигураций на сервер выполняется через toggle (активация/деактивация)
- Конфигурации можно редактировать даже когда Unit API недоступен
- Убедитесь, что Nginx Unit Control API доступен по указанному адресу (HTTP или Unix socket)

