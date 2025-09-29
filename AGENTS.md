# Repository Guidelines

## Project Structure & Module Organization
This Laravel + Livewire app keeps domain logic under `app/`. Filament admin resources live in `app/Filament`, interactive UI components in `app/Livewire`, and HTTP controllers in `app/Http`. Blade templates and frontend assets sit in `resources/views`, `resources/css`, and `resources/js`, while route definitions are under `routes/`. Database factories, seeders, and migrations stay inside `database/`.

## Build, Test, and Development Commands
Install dependencies with `composer install` for PHP and `bun install` for frontend tooling. Use `composer dev` to launch the PHP server and Vite via Bun concurrently; alternatively run `php artisan serve` and `bun run dev` separately. Build production assets with `bun run build`, and clear cached configs or views using `php artisan optimize:clear`.

## Coding Style & Naming Conventions
Adhere to PSR-12 PHP style with 4-space indentation and run `vendor/bin/pint` before every commit. Name Livewire components in StudlyCase (e.g., `TripPlanner`) with matching kebab-case Blade views (e.g., `trip-planner.blade.php`). Filament resources follow `SubjectResource` naming, and JavaScript modules in `resources/js` export camelCase functions.

## Testing Guidelines
Pest powers the suite located in `tests/Feature` and `tests/Unit`, with shared setup in `tests/TestCase.php`. Name files after the subject (e.g., `DestinationResourceTest.php`) and describe behavior using `it('...')` blocks. Run all tests via `./vendor/bin/pest`; use `php artisan test --parallel` for faster feedback, and refresh the SQLite database with `php artisan migrate:fresh --seed` when schema changes.

## Commit & Pull Request Guidelines
Follow the conventional prefix pattern from history (`feat:`, `fix:`, `chore:`) and keep the summary line under 72 characters. Reference related issues with `Refs #123` and add a short rationale in the body when the change is non-obvious. Pull requests should outline scope, list verification steps (tests, Pint), and attach screenshots for Filament UI adjustments; rebase on `main` before requesting review.

## Security & Configuration Tips
Duplicate `.env.example` into `.env`, update secrets locally, and avoid committing these files. Rotate the app key with `php artisan key:generate` in new environments. After dependency updates, review upstream changelogs—especially for Filament and Livewire—and note any migrations that require production coordination.
