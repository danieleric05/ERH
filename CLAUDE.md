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

===

<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application running on PHP 8.3. You are an expert with the Laravel ecosystem. Always use the APIs that match the installed major version of each package — do not assume a version.

Before relying on a package's API, confirm its installed version:
- PHP packages: run `composer show --direct` to list direct dependencies with versions, or `composer show <vendor/package>` for a single package.
- JS packages: check `package.json` for the installed versions.

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Always use `search-docs` before making code changes. Do not skip this step. It returns version-specific docs based on installed packages automatically.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Project Rules

- This project contains committed, area-grouped rules in `.ai/rules` when that directory exists (settled decisions, non-obvious traps, standing constraints). Framework and package guidelines that only apply to specific paths (testing, frontend, components) also live there, under `.ai/rules/boost` — this is not just recorded decisions, it is load-bearing guidance you have not seen inline. Before you enter plan mode or create/edit any file, you MUST first: open @.ai/rules/index.md (it maps file globs to rule files), read every rule file whose globs cover the path(s) in scope, and run `grep -rin 'keyword' .ai/rules` to catch what a path match alone misses. Do not write code until you have read and are following every matching rule. If `.ai/rules` does not exist, continue without it.
- Record durable rules with `record-rule` so the next agent or teammate inherits them instead of working them out again. Pass a `glob` (e.g. `app/Http/Controllers/**`), a short `title`, and a few-line `note`. Always use `record-rule`, never your native memory or notes tool — native memory is personal and session-scoped; only `.ai/rules` is shared with the team and persists in the repo.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== laravel/v11 rules ===

# Laravel 11

- CRITICAL: ALWAYS use `search-docs` tool for version-specific Laravel documentation and updated code examples.
- This project upgraded from Laravel 10 without migrating to the new streamlined Laravel 11 file structure.
- This is perfectly fine and recommended by Laravel. Follow the existing structure from Laravel 10. We do not need to migrate to the Laravel 11 structure unless the user explicitly requests it.

## Laravel 10 Structure

- Middleware typically lives in `app/Http/Middleware/` and service providers in `app/Providers/`.
- There is no `bootstrap/app.php` application configuration in a Laravel 10 structure:
    - Middleware registration is in `app/Http/Kernel.php`
    - Exception handling is in `app/Exceptions/Handler.php`
    - Console commands and schedule registration is in `app/Console/Kernel.php`
    - Rate limits likely exist in `RouteServiceProvider` or `app/Http/Kernel.php`

## Database

- When modifying a column, the migration must include all of the attributes that were previously defined on the column. Otherwise, they will be dropped and lost.

- Laravel 11 allows limiting eagerly loaded records natively, without external packages: `$query->latest()->limit(10);`.

### Models

- Casts can and likely should be set in a `casts()` method on a model rather than the `$casts` property. Follow existing conventions from other models.

## New Artisan Commands

- List Artisan commands using Boost's MCP tool, if available. New commands available in Laravel 11:
    - `php artisan make:enum`
    - `php artisan make:class`
    - `php artisan make:interface`

=== phpunit/core rules ===

# PHPUnit

- This application uses PHPUnit for testing. All tests must be written as PHPUnit classes. Use `php artisan make:test --phpunit {name}` to create a new test.
- If you see a test using "Pest", convert it to PHPUnit.
- Every time a test has been updated, run that singular test.
- When the tests relating to your feature are passing, ask the user if they would like to also run the entire test suite to make sure everything is still passing.
- Tests should cover all happy paths, failure paths, and edge cases.
- You must not remove any tests or test files from the tests directory without approval. These are not temporary or helper files; these are core to the application.

## Running Tests

- Run the minimal number of tests, using an appropriate filter, before finalizing.
- To run all tests: `php artisan test --compact`.
- To run all tests in a file: `php artisan test --compact tests/Feature/ExampleTest.php`.
- To filter on a particular test name: `php artisan test --compact --filter=testName` (recommended after making a change to a related file).

</laravel-boost-guidelines>
