# Cortex Copilot Instructions

## Overview

Rinvex Cortex is a Laravel-based enterprise framework with a **modular, multi-tenant architecture** fundamentally different from vanilla Laravel. Every feature is encapsulated as a module—even core functionality is modularized.

## Core Architecture

### Module Structure

**Modules are the fundamental building blocks.** All code lives in `app/modules/cortex/{module-name}/`.

Each module contains:
- `src/` – PHP classes (Models, Controllers, Requests, etc.)
- `resources/js/` – Module JavaScript, loaded dynamically
  - `app.js` – Auto-executed globally
  - `module.js` – Module-scoped functions called via `module('cortex/auth')`
  - `webpack.mix.js` – Module-specific webpack config included in main build
- `resources/views/` – Blade templates
- `database/migrations/` – Database migrations
- `routes/` – Route definitions
- `config/` – Module-specific configuration

**Key File:** Module webpack discovery in [webpack.mix.js#L123-L135](webpack.mix.js#L123-L135) auto-includes all module webpack.mix.js files.

### Multi-Area Architecture

The application supports four **access areas** with separate guards/auth:
- `adminarea` – System administrators
- `frontarea` – Public-facing users
- `managerarea` – Managers/content operators
- `tenantarea` – Tenant members (when multi-tenant is active)

Routes are namespaced by area: `routes/adminarea.php`, `routes/frontarea.php`, etc.

### Multi-Tenancy

Tenancy is core, configured in [config/rinvex.tenants.php](config/rinvex.tenants.php):
- Uses **Rinvex Tenants** package for domain/subdomain-based tenant resolution
- Tenantable models use polymorphic relationships via `tenantables` table
- Settings are tenant-aware (see `SettingTenantable` model in [config/rinvex.settings.php](config/rinvex.settings.php#L15))

## Critical Developer Workflows

### Installation & Setup

```bash
composer install
php artisan rinvex:migrate:tags
php artisan cortex:install  # Installs all modules (drop DB first if re-running)
npm install
npm run dev  # Builds webpack including all module assets
```

### Development Commands

```bash
# Watch mode for rapid development
npm run watch

# Build for production
npm run production

# Run tests
vendor/bin/phpunit

# Generate Laravel routes for JavaScript
php artisan laroute:generate

# Generate JS language phrases
php artisan lang:js
```

**Important:** Webpack rebuild is required when changing `.env` variables (because laroute binds route domains). Use `npm run dev` after `.env` changes.

### Module Creation

When adding a new module (e.g., `cortex/boards` mentioned in README):

1. Create directory: `app/modules/cortex/boards/`
2. Add service provider in module's `src/Providers/`
3. Create config at `config/rinvex.boards.php` if needed
4. Define routes in `resources/routes/{area}.php` files
5. Create webpack.mix.js to register module assets—**this is auto-discovered**
6. Implement models, controllers, migrations in standard Laravel structure
7. Module is auto-loaded via composer's package discovery

## Project-Specific Patterns

### Service Provider Loading Priorities

Service providers load in priority order (see [config/app.php#L182-L210](config/app.php#L182-L210)):
1. `priority_1` – Laravel core providers
2. `priority_2` – Vendor packages (Cortex Foundation loads here)
3. `priority_3` – Cortex modules and extensions
4. `priority_4` – Application providers

This prevents early binding issues when modules depend on core setup.

### Tenant-Aware Routing

Routes automatically respect tenant context. When defining routes, use tenant domain placeholders:

```php
// In app/modules/cortex/mymodule/resources/routes/tenantarea.php
Route::domain('{tenant_domain}')
    ->middleware(['web', 'auth:tenantarea'])
    ->group(function () {
        Route::post('/boards/{id}/posts', 'PostController@store')->name('posts.store');
    });
```

The `{tenant_domain}` placeholder is bound by laroute based on the current tenant. After modifying `.env` variables (like `APP_DOMAIN` or `SESSION_DOMAIN`), rebuild webpack: `npm run dev`.

### Frontend Integration

**JavaScript Module Loading** ([resources/js/app.js#L446](resources/js/app.js#L446)):

```javascript
// Auto-execute module's app.js globally
window.module = async (name, ...args) => 
  await import(`../../app/modules/${name}/resources/js/module`).then(res => res.default(...args));

// Also supports extensions (third-party packages)
window.extension = async (name, ...args) => 
  await import(`../../app/extensions/${name}/resources/js/module`).then(res => res.default(...args));
```

**Broadcasting with Pusher/Echo** ([resources/js/app.js#L50-L62](resources/js/app.js#L50-L62)):

```javascript
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    authEndpoint: routes.route(window.Cortex.accessarea + '.broadcast'),
    forceTLS: true
});

// Listen for real-time events in your module
window.Echo.channel(`board.${boardId}`)
    .listen('PostCreated', (event) => {
        // Update UI with new post
    });
```

The auth endpoint dynamically routes through the current access area (adminarea, tenantarea, etc.).

**CSS Purging:** PurgeCSS whitelist in [webpack.mix.js#L87](webpack.mix.js#L87) includes admin theme classes. Add custom selectors if CSS is stripped in production.

### Database & ORM

- **Factory:** Uses Laravel's built-in factory pattern with Faker
- **Migrations:** Standard Laravel migrations, auto-discovered from modules
- **Localization:** Uses Spatie's `translatable` package—models have `translatable` attributes
- **Media:** Spatie's `media-library` integrated for file attachments

### Authentication & Authorization

Cortex extends Laravel's auth with:
- **Multi-guard setup** per area (adminarea, frontarea, etc.) in [config/auth.php](config/auth.php)
- **Bouncer** integration (check [config/app.php](config/app.php) for Bouncer alias)
- **Sanctum** for API authentication

## Configuration Files to Know

| File | Purpose |
|------|---------|
| [config/rinvex.tenants.php](config/rinvex.tenants.php) | Tenant resolver, domain binding |
| [config/rinvex.settings.php](config/rinvex.settings.php) | Settings types (text, radio, dropdown, etc.) |
| [config/auth.php](config/auth.php) | Guards per area, provider configuration |
| [config/app.php](config/app.php) | Provider loading priorities, aliases |
| [webpack.mix.js](webpack.mix.js) | Module discovery, asset compilation |

## Key Dependencies

- **Laravel 11** – Framework foundation
- **Spatie Packages** – Media library, activity logging, DB snapshots, translatable
- **Rinvex Packages** – Tenants, categories, tags, oauth, pages, foundation
- **Admin LTE** – Backend theme
- **TinyMCE** – WYSIWYG editor
- **DataTables** – Server-side table rendering
- **Pusher** – Real-time broadcasting

## Common Tasks

### Add a Model with Translations

```php
namespace Cortex\MyModule\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Post extends Model {
    use HasTranslations;
    public array $translatable = ['title', 'body'];
}
```

### Register a Module Command

Add to module's service provider `register()` method:
```php
$this->commands([YourCommand::class]);
```

### Add Module Routes

Create `app/modules/cortex/mymodule/resources/routes/adminarea.php` and register in service provider:
```php
Route::middleware('web')->group(base_path('app/modules/cortex/mymodule/resources/routes/adminarea.php'));
```

### Publish Module Assets

Use `php artisan vendor:publish --provider="Cortex\\MyModule\\Providers\\MyModuleServiceProvider"` to make config/migrations discoverable.

## Testing

- **PHPUnit config:** [phpunit.xml](phpunit.xml) runs `tests/Unit` and `tests/Feature`
- **Use CreatesApplication trait** ([tests/CreatesApplication.php](tests/CreatesApplication.php)) in test classes
- **Bootstrap:** Tests use standard Laravel bootstrap via `bootstrap/app.php`
- **Strict mode enabled:** All tests must explicitly test something (no empty tests)

## Debugging

- **Clockwork** – Performance profiling at `/__clockwork/`
- **Debugbar** – Laravel debug toolbar (dev dependency)
- **IDE Helper** – `php artisan ide-helper:generate` generates model hints
- **Query Detector** – Alerts on N+1 query issues during development

---

**Last Updated:** 2026-01-28

For detailed module examples, examine `app/modules/cortex/auth-tenantable/` or `app/modules/cortex/pages-tenantable/`.
