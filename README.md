# AppsRecord

App-store style portfolio for showcasing apps by category. Users register, publish apps (name, description, logo, up to 3 screenshots), and everything appears on the public landing page.

## Stack

- Laravel 12 + Blade
- Laravel Breeze (auth)
- Tailwind CSS
- SQLite (default)

## Design system

UI follows the **ui-ux-pro-max** design system in `design-system/appsrecord/MASTER.md`:

- Flat / monochrome + blue accent (`#2563EB`)
- Fonts: Archivo + Space Grotesk
- App Store–style category browsing

## Setup

```bash
composer install
cp .env.example .env   # if needed
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm install
npm run build
php artisan serve
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000).

### Demo account (after seed)

- Email: `demo@appsrecord.test`
- Password: `password`

## Google login

1. Open [Google Cloud Console](https://console.cloud.google.com/) → APIs & Services → Credentials
2. Create an **OAuth 2.0 Client ID** (Web application)
3. Add authorized redirect URI:
   ```
   http://127.0.0.1:8000/auth/google/callback
   ```
4. Put the values in `.env`:
   ```env
   APP_URL=http://127.0.0.1:8000
   GOOGLE_CLIENT_ID=your-client-id
   GOOGLE_CLIENT_SECRET=your-client-secret
   GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
   ```
5. Clear config cache: `php artisan config:clear`
6. On login/register, click **Continue with Google**

If an existing email account signs in with Google, the Google ID is linked to that user.

## Categories (seeded)

Productivity, Business, Education, Health & Fitness, Finance, Entertainment, Social, Utilities

## Dev

```bash
npm run dev
php artisan serve
```
