<p align="center">
  <img src="https://laravel.com/img/logomark.min.svg" alt="Laravel Logo" width="80" />
  <span style="font-size:2rem; vertical-align: middle; margin-left: .5rem;">ZBV Invitational</span>
</p>

<p align="center">
  <strong>Storefront & Registration Portal for the ZBV Invitational Golf Tournament</strong><br>
  Built with Laravel 12, Inertia.js & Vue 3
</p>

---

## TODO

- [ ] Improve test coverage
- [ ] Offload gallery images into an external file host
- [ ] Add additional admin controls

---

## Table of Contents

- [Tech Stack](#tech-stack)  
- [Prerequisites](#prerequisites)  
- [Installation](#installation)  
- [Environment Setup](#environment-setup)  
- [Database & Seeding](#database--seeding)  
- [Running Locally](#running-locally)  
- [Authentication & Authorization](#authentication--authorization)  
- [Directory Structure](#directory-structure)  
- [Testing](#testing)  
- [Code Quality](#code-quality)  
- [Deployment](#deployment)  
- [License](#license)  

---

## Tech Stack

- **Backend**: PHP 8.2 · Laravel 12 · Sanctum
- **Frontend**: Vue 3 · TypeScript · Inertia.js · Vite  
- **Styling**: SCSS · Bulma · PrimeVue · lucide-vue-next icons  
- **Data**: PostgreSQL  
- **Payments**: Stripe.js / stripe-php  
- **Testing**: PHPUnit 12
- **Quality**: Larastan / PHPStan / Pint / Prettier / ESLint  

---

## Prerequisites

- PHP 8.2+  
- Composer  
- Node.js 16+ & npm / Yarn  
- (Optional) Docker & Docker Compose for Laravel Sail  

---

## Installation

```bash
# 1. Clone the repo
git clone https://github.com/andydeforest/zbvinvitational.com.git
cd zbvinvitational.com

# 2. Install PHP dependencies
composer install

# 3. Install JS dependencies
npm ci

# 4. Copy .env and generate key
cp .env.example .env
php artisan key:generate
```

---

## Environment Setup

1. **Configure `.env`**  
   ```env
   APP_NAME="ZBV Invitational"
   APP_URL=http://localhost

   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=
   DB_USERNAME=
   DB_PASSWORD=

   STRIPE_KEY=your_stripe_public_key
   STRIPE_SECRET=your_stripe_secret_key
   STRIPE_WEBHOOK_SECRET=your_webhook_secret
   ```

   ---

## Database & Seeding

Run migrations and seeders in one command:
```bash
php artisan migrate
```

## Running Locally

### Asset Compilation
- **Development (hot reload)**
  ```bash
  npm run dev
  ```
- **Production build**
  ```bash
  npm run build
  ```

### Serving the App
- **Native**
  ```bash
  php artisan serve
  ```

## Authentication & Authorization

- **API:** Protected routes guarded by Sanctum
- **Admin:** All `/admin/*` routes use the `auth` and `verified` middleware
- **Login/Registration:** Inertia-powered pages under `resources/js/Pages/Auth/`

## Directory Structure

```
app/
├── Actions/
├── Console/
├── Enums/
├── Events/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   └── Public/
│   └── Middleware/
├── Models/
├── Products/
├── Services/
└── ...
config/
database/
resources/
├── js/
│   ├── Components/
│   ├── Composables/
│   ├── Pages/
│   └── app.ts
├── scss/
└── views/
routes/
├── admin.php
├── api.php
└── web.php
tests/
├── Feature/
└── Unit/
```

## Testing

```bash
# Run PHP tests
php artisan test

# Via Sail
./vendor/bin/sail test

# Lint JS/TS
npm run lint
npm run lint:fix
```

- **Unit** tests cover services (e.g. StripeGateway, ProductMetadataService) and models
- **Feature** tests cover HTTP endpoints, Inertia responses, and webhook handling

## Code Quality

- **Static Analysis**
  ```bash
  vendor/bin/phpstan analyse
  vendor/bin/larastan
  ```
- **Formatting & Style**
  ```bash
  vendor/bin/pint
  npm run lint:fix
  ```

## Deployment

1. **Compile assets**
   ```bash
   npm run build
   ```
2. **Migrate production database**
   ```bash
   php artisan migrate --force
   ```
3. **Queue & Scheduler**
   ```bash
   php artisan queue:work
   php artisan schedule:work
   ```

## License

This project is open-sourced software licensed under the [MIT License](LICENSE).
