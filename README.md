# OnePazz

A Laravel-based gym membership management platform for Cambodia. Employers subscribe to a plan, manage their employees' gym access, and pay invoices — while gym owners list their facilities and a platform admin oversees everything.

## Features

- **Gym directory** — browse and view gyms with map (Leaflet.js), reviews, and classes
- **Employer dashboard** — manage employees, track check-ins, submit invoice payments
- **Employee management** — invite by email, suspend/restore access
- **Billing** — invoice generation, payment upload, PDF/CSV report exports
- **Platform admin panel** — approve gym applications, confirm/reject payments, configure platform settings
- **Multi-language & multi-currency** — switch locale and currency per session
- **Role-based access** — `platform_admin`, `employer_admin`, `employee`

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 13, PHP 8.3+ |
| Frontend | Blade, Tailwind CSS v3, Alpine.js |
| Maps | Leaflet.js |
| PDF | barryvdh/laravel-dompdf |
| Build | Vite |
| Database | MySQL / SQLite |

## Requirements

- PHP 8.3+
- Composer
- Node.js 18+
- A database (MySQL or SQLite)

## Setup

```bash
# 1. Clone the repo
git clone https://github.com/ouchkemvanra/Onepazz-frontend.git onepazz
cd onepazz

# 2. One-command setup (installs deps, copies .env, generates key, migrates, builds assets)
composer run setup
```

Then edit `.env` to configure your database and mail driver.

## Development

```bash
composer run dev
```

This starts Laravel, the queue worker, Pail log viewer, and Vite dev server concurrently.

## Running Tests

```bash
composer run test
```

## Roles

| Role | Access |
|---|---|
| `platform_admin` | Admin panel — approve gyms, confirm payments, platform settings |
| `employer_admin` | Dashboard — employees, billing, reports |
| `employee` | Profile, gym check-ins |

## License

MIT
