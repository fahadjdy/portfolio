# Fahad Jadiya — Portfolio

A fully dynamic portfolio for a Senior Full-Stack Developer (**fahad-jadiya.com**). Public
marketing site is server-rendered Blade (SEO/AEO/GEO-first); the admin panel is an
Inertia + Vue 3 SPA. Everything — skills, experience, projects (rich panel-by-panel case
studies), services, testimonials, leads and site settings — is managed from the admin.

## Stack
- **Backend:** Laravel 12 (PHP 8.2+), MySQL/MariaDB
- **Public site:** Blade + Tailwind CSS + Alpine.js (separate lightweight Vite bundle)
- **Admin panel:** Inertia.js + Vue 3 + TypeScript + Tailwind (Metronic-style)
- **Images:** intervention/image (GD) → WebP + responsive srcset
- **Cache/session/queue:** `database` driver (shared-hosting friendly)

## Local setup
```bash
cp .env.example .env          # set DB + ADMIN_* + MAIL_*
php artisan key:generate
php artisan migrate --seed     # seeds admin user + all demo content
php artisan storage:link
npm install && npm run build   # or: npm run dev
composer run dev               # serve + queue + vite together
```
Admin login: `fahadjdy12@gmail.com` / value of `ADMIN_PASSWORD` (change it in the admin after first login).

## Key URLs
- Public: `/`, `/about`, `/projects`, `/projects/{slug}`, `/services`, `/contact`
- SEO: `/sitemap.xml`, `/robots.txt`, `/llms.txt` (all dynamic)
- Admin: `/admin/dashboard` (login at `/login`; no public registration)

## SEO / AEO / GEO
- Per-page title/meta/canonical/OG/Twitter driven by DB settings + per-model fields
- JSON-LD `@graph` per page (Person, WebSite, BreadcrumbList, FAQPage, per-project
  SoftwareApplication/CreativeWork with `featureList`, per-service Service)
- Answer-first copy + crawlable `<details>` FAQ blocks; `/llms.txt` for AI engines

## Deployment (shared hosting via GitHub Actions FTPS)
Push to `master` → `.github/workflows/deploy.yml` builds assets + production Composer
deps and uploads over explicit FTPS (`.github/deploy.lftp`).

**First-time server setup:**
1. Create `.env` on the server (from `.env.example`); set real DB creds, `APP_KEY`,
   `APP_ENV=production`, `APP_DEBUG=false`, `MAIL_*` (SMTP for lead emails), `DEPLOY_TOOLS=true`.
2. Ensure the domain root maps to `public_html` (root `.htaccess` forwards into `public/`),
   or point the docroot directly at `public/`.
3. After the first deploy, hit (the workflow also calls these automatically):
   `/deploy/init` → `/deploy/migrate` → `/deploy/seed` → `/deploy/link` → `/deploy/cache`
4. Set `DEPLOY_TOOLS=false` once stable to lock the deploy routes.

Required GitHub secret: `FTP_PASSWORD` (user `fahadja2`, host `ftp.fahad-jadiya.com`).

## Notes
- The blog tables are scaffolded (migrations + models) but the blog UI is intentionally
  deferred (optional). Toggle `blog_enabled` in Settings when the UI is added.
- A stray `vendor.tar` exists in early git history; it is removed from HEAD and ignored.
  Optionally purge it from history with `git filter-repo` if a lean clone matters.
