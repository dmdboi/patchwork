# patchwork

A filamentPHP-based content management system for the web.

## Installation

```bash
composer install

php artisan migrate

php artisan db:seed
```

## Usage

Start the Laravel app with `php artisan serve` and navigate to `http://localhost:8000` in your browser.

## DB Seed

The DB seed will create a user with the following credentials:

- 👤 admin@example.com
- 🔐 password

Login at `/admin/login` to access the admin panel.