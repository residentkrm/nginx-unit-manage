# Nginx Unit Management System

Web-based management system for Nginx Unit configuration via web interface and API.

## Features

- [x] Applications
- [x] Listeners
- [x] Routes
- [x] Configuration storage in database
- [x] Unix socket support for Unit Control API
- [ ] SSL/TLS certificate management
- [ ] Custom access log configuration
- [ ] Proxying configuration

## Setup

### 1. Environment Variables

Add to `.env` file:

```env
UNIT_API_URL=unix:/opt/homebrew/var/run/unit/control.sock
# or for HTTP:
# UNIT_API_URL=http://127.0.0.1:8500
UNIT_API_TIMEOUT=10
UNIT_VERIFY_SSL=false
```

### 2. Run Migrations

Migrations are already executed, but if needed:

```bash
php artisan migrate
```

### 3. Install Dependencies

```bash
# PHP dependencies
composer install

# JavaScript dependencies
npm install
```

## Running

### Development

Start the Laravel development server:

```bash
php artisan serve
```

In another terminal, start the Vite development server:

```bash
npm run dev
```

The application will be available at `http://localhost:8000`

### Production Build

Build assets for production:

```bash
npm run build
```

## Requirements

- PHP 8.2+
- Laravel 12+
- Nginx Unit with Control API enabled
- MySQL/MariaDB

## Notes

- All changes are saved to the database regardless of Unit API state
- Configuration deployment to the server is performed via toggle (activate/deactivate)
- Configurations can be edited even when Unit API is unavailable
- Ensure that Nginx Unit Control API is accessible at the specified address (HTTP or Unix socket)
