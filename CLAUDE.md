# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Quick Start

### Development Setup
```bash
# Install PHP dependencies
composer install

# Install npm dependencies
npm install

# Set up environment
cp .env.example .env
php artisan key:generate

# Run migrations (if needed)
php artisan migrate

# Build assets
npm run dev          # Development build with watch
npm run production   # Production build
npm run watch        # Watch mode for assets
```

### Common Commands

**Testing**
```bash
php artisan tinker                    # Interactive PHP shell with Laravel context
./vendor/bin/phpunit                  # Run all tests
./vendor/bin/phpunit tests/Unit       # Run unit tests only
./vendor/bin/phpunit tests/Feature    # Run feature tests only
```

**Database**
```bash
php artisan migrate                   # Run migrations
php artisan migrate:fresh             # Drop all tables and re-run migrations
php artisan tinker                    # Query database interactively
```

**Development Server**
```bash
php artisan serve                     # Start dev server on http://localhost:8000
```

**Artisan Utilities**
```bash
php artisan route:list                # List all routes
php artisan config:cache              # Cache config (for production)
```

## Project Architecture

### Application Type
HR Management System (ERH - Espace de Ressources Humaines) for managing workers, recruitment, contracts, health records, sanctions, and variables.

### Tech Stack
- **Backend**: Laravel 8.83.29 with PHP 8.0+
- **Frontend**: Bootstrap 4, jQuery 3.x, Vue 2.x with Laravel Mix
- **Database**: MySQL (connected via .env configuration)
- **Excel**: Maatwebsite/Excel 3.1.67 (recently migrated from v2 to v3 API)
- **PDF**: Barryvdh Laravel DomPDF for PDF generation
- **Testing**: PHPUnit 9.5

### Directory Structure

**Core Laravel Structure**
- `app/` - Application models and business logic
  - `Models/` - Eloquent models (Travailleur, Missions, Equipes, etc.)
  - `Http/Controllers/` - Route controllers
    - `EmployerController` - Worker/employee management
    - `RecruController` - Recruitment/HR operations
    - `ConfigController` - System configuration
    - `SanctionController` - Discipline/sanctions
    - `SanteController` - Health/medical records
    - `VariablesController` - System variables
  - `Console/` - Artisan commands
  - `Providers/` - Service providers

- `config/` - Configuration files for database, app, cache, etc.
- `database/` - Migrations and seeders
- `routes/` - Route definitions (main routes in `web.php`)
- `resources/` - Views and raw assets (blade templates, CSS, JS)
- `tests/` - PHPUnit test suites (Unit and Feature tests)
- `storage/` - Application-generated files (logs, sessions, uploads)

**Asset Management**
- `webpack.mix.js` - Laravel Mix configuration for asset compilation
- `resources/` - Raw assets (sass, js, blade templates)
- `node_modules/` - npm dependencies (gitignored)

### Key Models & Entities
The `app/` directory contains ~30 Eloquent models representing HR/organizational data:
- **People**: Travailleur (worker), User
- **Organization**: Unites, Equipes, Departement
- **Employment**: Missions, Conges, TypeContrat, Fonction
- **Health**: Santes, Sante
- **Compliance**: Sanctions, Autorisations, Precarites, AccidentTravail
- **System**: Variables, Communes, Pays, Categories

### Routes & Endpoints
All routes defined in `routes/web.php` with authentication middleware. Key sections:
- **Recruitment**: `/erh/recrutement`, `/inscription-ouvrier`, `/liste-complte-travailleur`
- **Workers**: `/liste-embauches`, `/ajouter-travailleur-*`, `/ajouter-autres-travailleur`
- **Dashboard**: `/bienvenue` (returns dashboard with counts and alerts)
- **Reports/Exports**: Various Excel export endpoints (handled by controllers)

Routes are grouped with `auth` middleware - all routes require login.

### Excel Export System
Recent refactoring migrated from Maatwebsite\Excel v2 to v3:
- Exports use anonymous classes instead of separate ArrayExport classes
- Main controllers (RecruController, EmployerController) handle export methods
- All exports include "Commune" column (recently completed implementation)
- Export formats: XLSX with formatted cells, row heights, column widths

## Important Git Context

### Repository Structure
The git repo contains ONLY the Laravel application code. Public assets (ajax/, css/, js/, rhassets/, etc.) live in the **parent directory on production**, NOT in the repo. The `public/` directory should NOT exist in the repo - this was cleaned up in recent commits.

### Recent Work
1. **Excel Exports**: Migrated from v2 to v3 API, refactored to use anonymous classes
2. **Architecture**: Removed `public/` directory from git (keeping repo clean)
3. **Bug Fixes**: Fixed malformed routes and debug code (dd() calls)

### Branch Consistency
All branches should follow the same architecture - no `public/` directory in git. A `fix_architecture.sh` script is available to clean up branches if needed.

## Code Patterns & Conventions

### Models
Eloquent models are at the root of `app/` (no separate Models folder). Relationships and accessors are defined inline.

### Controllers
- Thin controllers that delegate business logic to models
- Main controllers: EmployerController, RecruController
- Mix of route methods and separate action methods
- Some routes defined inline in `web.php` with closure callbacks

### Views
Blade templates in `resources/views/` organized by feature:
- `home/` - Dashboard and profile views
- `menu/recrutement/` - Recruitment views
- `travailleur/` - Worker management views

### Migrations
Located in `database/migrations/` with timestamps. Run via `php artisan migrate`.

## Environment & Dependencies

### Required Environment Variables
See `.env.example` for full list. Key variables:
- `APP_NAME`, `APP_ENV`, `APP_DEBUG`, `APP_URL`
- `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `MAIL_*` variables for email configuration

### PHP Extensions
Standard Laravel requirements - check `composer.json` for dependencies. Key packages:
- `laravel/framework` 8.83
- `maatwebsite/excel` 3.1 (for Excel export)
- `barryvdh/laravel-dompdf` 2.2 (for PDF generation)
- `laravelcollective/html` 6.4 (for HTML builders)

## Testing

Tests organized in `tests/Unit` and `tests/Feature`. Configuration in `phpunit.xml`:
- Uses separate test database (array cache, array session)
- Code coverage tracked for `app/` directory
- Both test suites run with `./vendor/bin/phpunit`

## Notes for Future Work

- **Vue 2**: Frontend uses Vue 2.x - note this when updating dependencies (Vue 3 migration would be breaking)
- **Asset Pipeline**: Uses Laravel Mix v2 - upgrades should be tested carefully
- **Excel Exports**: New v3 API uses anonymous classes - follow this pattern for new exports
- **Authentication**: Routes require auth middleware - check HomeController for login logic
- **Database**: No ORM relationships visible in models - they may be minimal or defined inline
