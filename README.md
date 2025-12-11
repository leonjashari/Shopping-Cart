## Shopping Cart Challenge

Laravel 12 + Breeze (Livewire/Tailwind) simple e-commerce cart built to mirror WonderWraps conventions (services/actions, Livewire components, scheduled jobs).

### Stack
- Laravel 12, PHP 8.4 (required by Symfony 8 packages)
- Livewire 3 + Tailwind
- MySQL
- Queue: database

### Setup
```bash
cp .env.example .env
# Set DB creds (MySQL) and ADMIN_EMAIL
php artisan key:generate
php artisan migrate --seed
npm install
npm run dev   # or npm run build
php artisan serve  # or use Herd: http://shopping-cart.test
```

Seeded login: `demo@example.com` / `password`

### Features
- Products listing with add-to-cart per authenticated user.
- Cart management (update/remove) backed by DB, not session/local storage.
- Checkout converts cart to order, decrements stock.
- Low-stock notification job queued to ADMIN_EMAIL.
- Daily sales report scheduled at 22:00 via `routes/console.php`.
- Livewire UI: catalog page, navbar cart badge, cart sidebar.

### Jobs & Mail
- `SendLowStockNotification` → `LowStockMail`
- `SendDailySalesReport` → `DailySalesReportMail`
- Admin recipient configured via `config/mail.php` (`ADMIN_EMAIL`).

### Tests
Add Laravel/Pest tests as needed (not included here due to time). Recommended: service tests for `CartService` and job/mailable assertions.
