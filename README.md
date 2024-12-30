# patchwork

A filamentPHP-based content management system for the web.

## Installation

```bash
composer install && npm install
```

Copy the `.env.example` file to `.env` and generate a new application key:

```bash
cp .env.example .env
php artisan key:generate
```

Run migrations and seeder
```
php artisan migrate

php artisan db:seed
```

Don't forget to link your storage too
```
php artisan storage:link
```

## Usage

Start the Laravel app with `php artisan serve` and navigate to `http://localhost:8000` in your browser.

## DB Seed

The DB seed will create a user with the following credentials:

- 👤 admin@example.com
- 🔐 password

Login at `/admin/login` to access the admin panel.
