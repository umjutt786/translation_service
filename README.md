
# Laravel Translation Management API

This project is a **Translation Management Service** built with Laravel 12.  
It allows storing, retrieving, updating, and exporting translations for multiple locales with tag-based filtering.  
It includes **token-based authentication**, **Swagger documentation**, and **Docker support** for easy setup.

---

## 📦 Features

- Store translations for multiple locales (e.g., `en`, `fr`, `es`)
- Tag translations for context (`web`, `mobile`, `desktop`)
- Search translations by locale, key, or tag
- Export translations as JSON for frontend frameworks (Vue.js, React, etc.)
- Token-based authentication with Laravel Sanctum
- Full Swagger/OpenAPI documentation
- Performance optimization with caching
- Dockerized environment for easy deployment

---

## 🚀 Getting Started

### Requirements

- PHP 8.2+
- Composer
- Docker & Docker Compose
- MySQL or PostgreSQL

---

### 1. Clone the Repository

```bash
git clone https://github.com/umjutt786/translation-service.git
cd translation-service
```

---

### 2. Docker Setup

Run Docker containers for Laravel and MySQL:

```bash
docker-compose up -d --build
```

This will spin up:

- Laravel app
- MySQL database

---

### 3. Environment Setup

Copy `.env.example` to `.env` and update settings:

```bash
cp .env.example .env
```

Update database settings and Sanctum configuration in `.env`:

```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=translations
DB_USERNAME=root
DB_PASSWORD=root

SANCTUM_STATEFUL_DOMAINS=localhost
```

---

### 4. Install Dependencies

```bash
docker exec -it translation-service_app composer install
```

---

### 5. Run Migrations & Seed Database

```bash
docker exec -it translation-service_app php artisan migrate
docker exec -it translation-service_app php artisan db:seed
```

You can also seed **100,000 translations** for testing:

```bash
docker exec -it translation-service_app php artisan tinker
\App\Models\Translation::factory()->count(100000)->create();
```

---

### 6. Generate Swagger Documentation

```bash
docker exec -it translation-service_app php artisan l5-swagger:generate
```

Access API docs at:

```
http://localhost:9000/api/documentation
```

---

## 📚 API Endpoints

| Endpoint                           | Method | Description                              |
|------------------------------------|--------|------------------------------------------|
| `/api/translations`               | GET    | List translations with filters          |
| `/api/translations`               | POST   | Create a new translation                 |
| `/api/translations/{id}`          | PUT    | Update a translation                     |
| `/api/translations/export/{locale}` | GET  | Export translations for a locale        |

---

### Example: Export Translations

```bash
curl -H "Authorization: Bearer {token}"      http://localhost:9000/api/translations/export/en
```

Response:
```json
{
  "welcome_message": "Welcome to our site",
  "logout": "Logout"
}
```

---

## 🛡 Authentication

Uses Laravel Sanctum for token-based authentication.

Generate a token for a user:
```bash
docker exec -it translation-service_app php artisan tinker
$user = \App\Models\User::factory()->create(['email' => 'test@example.com', 'password' => bcrypt('password')]);
$token = $user->createToken('api-token')->plainTextToken;
```

Use the token in requests:
```
Authorization: Bearer {token}
```

---

## 📜 Swagger Documentation

Swagger docs are automatically generated via **L5 Swagger**.

Visit:
```
http://localhost:9000/api/documentation
```

Here you can test API endpoints directly.

---

## 🧪 Testing

Run all tests:
```bash
docker exec -it translation-service_app php artisan test
```

Run a specific test:
```bash
docker exec -it translation-service_app php artisan test --filter=TranslationExportTest
```

---

## 🛠 Design Choices

- **Laravel 12**: Latest version for performance and long-term support
- **Repository Pattern**: Keeps controllers clean and logic reusable
- **Sanctum**: Lightweight API authentication
- **Caching**: Improves export endpoint speed (< 500ms)
- **Swagger**: Fully documented API with interactive UI
- **Docker**: Easy development and deployment

---

## 📂 Project Structure

```
translation-service/
├── app/
│   ├── Http/Controllers/Api/
│   ├── Models/
│   ├── Repositories/
├── config/
│   ├── l5-swagger.php
├── database/
│   ├── factories/
│   ├── migrations/
│   ├── seeders/
├── routes/
│   ├── api.php
├── tests/
│   ├── Feature/
│   ├── Unit/
├── docker-compose.yml
├── Dockerfile
├── README.md
```

---

## ⚡ Performance

- `GET /api/translations/export/{locale}` uses caching to ensure responses under 500ms.
- Database indexed on `locale` and `key` for faster search.

---

## 📜 License

MIT License © 2025 — Usama Zafar
