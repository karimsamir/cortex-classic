# Custom Package Templates for Laravel 12 Cortex Compatibility

This document provides ready-to-use templates for creating custom Cortex packages compatible with Laravel 12.

---

## 1. Create Custom cortex-foundation Package

### Step 1: Create Directory Structure

```bash
mkdir -p packages/cortex/foundation/src/Providers
mkdir -p packages/cortex/foundation/src/Contracts
mkdir -p packages/cortex/foundation/src/Exceptions
mkdir -p packages/cortex/foundation/src/Support
cd packages/cortex/foundation
```

### Step 2: Create composer.json

**File:** `packages/cortex/foundation/composer.json`

```json
{
    "name": "cortex/foundation",
    "description": "Cortex Foundation - Laravel 12 Compatible",
    "type": "library",
    "license": "MIT",
    "keywords": ["cortex", "foundation", "laravel"],
    "authors": [
        {
            "name": "Custom Build",
            "email": "your-email@example.com"
        }
    ],
    "require": {
        "php": "^8.3",
        "laravel/framework": "^12.0",
        "illuminate/contracts": "^12.0",
        "illuminate/support": "^12.0"
    },
    "autoload": {
        "psr-4": {
            "Cortex\\Foundation\\": "src/"
        }
    },
    "extra": {
        "laravel": {
            "providers": [
                "Cortex\\Foundation\\Providers\\FoundationServiceProvider"
            ]
        }
    },
    "minimum-stability": "stable"
}
```

### Step 3: Create Service Provider

**File:** `packages/cortex/foundation/src/Providers/FoundationServiceProvider.php`

```php
<?php

declare(strict_types=1);

namespace Cortex\Foundation\Providers;

use Illuminate\Support\ServiceProvider;

class FoundationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/cortex.php',
            'cortex'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/cortex.php' => config_path('cortex.php'),
        ], 'cortex-config');

        $this->loadViewsFrom(
            __DIR__ . '/../../resources/views',
            'cortex'
        );
    }
}
```

### Step 4: Create Config File

**File:** `packages/cortex/foundation/config/cortex.php`

```php
<?php

return [
    /**
     * Cortex Configuration
     */
    'name' => 'Cortex',
    'version' => '12.0.0',
    'description' => 'Cortex Foundation for Laravel 12',

    /**
     * Module Configuration
     */
    'modules' => [
        'enabled' => true,
        'path' => app_path('modules'),
    ],

    /**
     * Features
     */
    'features' => [
        'multi_language' => true,
        'multi_tenant' => false,
    ],
];
```

### Step 5: Create Base Traits and Contracts

**File:** `packages/cortex/foundation/src/Contracts/Macroable.php`

```php
<?php

declare(strict_types=1);

namespace Cortex\Foundation\Contracts;

interface Macroable
{
    /**
     * Register a custom macro.
     */
    public static function macro(string $name, callable $macro): void;

    /**
     * Register a custom macro.
     */
    public static function mixin(string $class, bool $replace = true): void;

    /**
     * Check if a macro is registered.
     */
    public static function hasMacro(string $name): bool;
}
```

**File:** `packages/cortex/foundation/src/Support/helpers.php`

```php
<?php

declare(strict_types=1);

if (!function_exists('cortex')) {
    /**
     * Get Cortex instance or configuration value.
     */
    function cortex(?string $key = null): mixed
    {
        if ($key === null) {
            return app('cortex');
        }

        return config('cortex.' . $key);
    }
}
```

---

## 2. Create Custom Breadcrumbs Package

### Step 1: Create Directory Structure

```bash
mkdir -p packages/diglactic/laravel-breadcrumbs/src
mkdir -p packages/diglactic/laravel-breadcrumbs/resources/views
cd packages/diglactic/laravel-breadcrumbs
```

### Step 2: Create composer.json

**File:** `packages/diglactic/laravel-breadcrumbs/composer.json`

```json
{
    "name": "diglactic/laravel-breadcrumbs",
    "description": "Breadcrumbs generator for Laravel 12",
    "type": "library",
    "license": "MIT",
    "require": {
        "php": "^8.3",
        "laravel/framework": "^12.0"
    },
    "autoload": {
        "psr-4": {
            "Diglactic\\Breadcrumbs\\": "src/"
        },
        "files": ["src/helpers.php"]
    },
    "extra": {
        "laravel": {
            "providers": [
                "Diglactic\\Breadcrumbs\\BreadcrumbsServiceProvider"
            ]
        }
    }
}
```

### Step 3: Create Service Provider

**File:** `packages/diglactic/laravel-breadcrumbs/src/BreadcrumbsServiceProvider.php`

```php
<?php

declare(strict_types=1);

namespace Diglactic\Breadcrumbs;

use Illuminate\Support\ServiceProvider;

class BreadcrumbsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(BreadcrumbsManager::class, function ($app) {
            return new BreadcrumbsManager($app);
        });

        $this->app->alias(BreadcrumbsManager::class, 'breadcrumbs');
    }

    public function boot(): void
    {
        $this->loadViewsFrom(
            __DIR__ . '/../resources/views',
            'breadcrumbs'
        );

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/breadcrumbs'),
        ]);
    }
}
```

### Step 4: Create Breadcrumbs Manager

**File:** `packages/diglactic/laravel-breadcrumbs/src/BreadcrumbsManager.php`

```php
<?php

declare(strict_types=1);

namespace Diglactic\Breadcrumbs;

use Illuminate\Container\Container;
use Illuminate\Support\Collection;

class BreadcrumbsManager
{
    protected Collection $breadcrumbs;

    protected Container $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
        $this->breadcrumbs = collect();
    }

    /**
     * Add a breadcrumb.
     */
    public function add(string $title, ?string $url = null): static
    {
        $this->breadcrumbs->push([
            'title' => $title,
            'url' => $url,
        ]);

        return $this;
    }

    /**
     * Get all breadcrumbs.
     */
    public function get(): Collection
    {
        return $this->breadcrumbs;
    }

    /**
     * Render breadcrumbs.
     */
    public function render(string $view = 'breadcrumbs::default'): string
    {
        return view($view, ['breadcrumbs' => $this->breadcrumbs])->render();
    }

    /**
     * Reset breadcrumbs.
     */
    public function reset(): static
    {
        $this->breadcrumbs = collect();
        return $this;
    }
}
```

### Step 5: Create Helper Function

**File:** `packages/diglactic/laravel-breadcrumbs/src/helpers.php`

```php
<?php

declare(strict_types=1);

if (!function_exists('breadcrumbs')) {
    /**
     * Get breadcrumbs instance.
     */
    function breadcrumbs(): \Diglactic\Breadcrumbs\BreadcrumbsManager
    {
        return app('breadcrumbs');
    }
}
```

### Step 6: Create Default View

**File:** `packages/diglactic/laravel-breadcrumbs/resources/views/default.blade.php`

```blade
@if ($breadcrumbs->count())
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            @foreach ($breadcrumbs as $breadcrumb)
                <li class="breadcrumb-item @if ($loop->last) active @endif">
                    @if ($breadcrumb['url'] && !$loop->last)
                        <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                    @else
                        {{ $breadcrumb['title'] }}
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
```

---

## 3. Update Root composer.json

Add these repositories to your root `composer.json`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "packages/cortex/*"
        },
        {
            "type": "path",
            "url": "packages/diglactic/*"
        },
        {
            "type": "path",
            "url": "packages/Rinvex/*"
        }
    ],
    "require": {
        "laravel/framework": "^12.0",
        "cortex/foundation": "*",
        "diglactic/laravel-breadcrumbs": "*"
    }
}
```

---

## 4. Usage in Your Application

### Using Custom Foundation Package

**In `bootstrap/app.php`:**

```php
return Application::configure(basePath: __DIR__)
    ->withServiceProviders([
        Cortex\Foundation\Providers\FoundationServiceProvider::class,
    ])
    ->create();
```

### Using Custom Breadcrumbs

**In your controller:**

```php
namespace App\Http\Controllers;

use Diglactic\Breadcrumbs\BreadcrumbsManager;

class HomeController extends Controller
{
    public function index(BreadcrumbsManager $breadcrumbs)
    {
        $breadcrumbs->add('Home', route('home'));
        $breadcrumbs->add('Dashboard');

        return view('dashboard', [
            'breadcrumbs' => $breadcrumbs->get(),
        ]);
    }
}
```

**In your view:**

```blade
@include('breadcrumbs::default')
```

---

## 5. Common Customizations

### Adding Middleware Support

**File:** `packages/cortex/foundation/src/Middleware/CortexMiddleware.php`

```php
<?php

declare(strict_types=1);

namespace Cortex\Foundation\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CortexMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Your middleware logic here

        return $next($request);
    }
}
```

### Adding Facades

**File:** `packages/cortex/foundation/src/Facades/Cortex.php`

```php
<?php

declare(strict_types=1);

namespace Cortex\Foundation\Facades;

use Illuminate\Support\Facades\Facade;

class Cortex extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'cortex';
    }
}
```

---

## 6. Testing Your Custom Packages

### Basic Tests

**File:** `packages/cortex/foundation/tests/ServiceProviderTest.php`

```php
<?php

declare(strict_types=1);

namespace Cortex\Foundation\Tests;

use Orchestra\Testbench\TestCase;
use Cortex\Foundation\Providers\FoundationServiceProvider;

class ServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            FoundationServiceProvider::class,
        ];
    }

    public function test_package_is_registered()
    {
        $this->assertTrue(
            $this->app->bound('cortex')
        );
    }

    public function test_config_is_published()
    {
        $this->artisan('vendor:publish', [
            '--provider' => FoundationServiceProvider::class,
        ]);

        $this->assertFileExists(config_path('cortex.php'));
    }
}
```

### Run Tests

```bash
cd packages/cortex/foundation
composer require --dev orchestra/testbench
./vendor/bin/phpunit
```

---

## 7. Publishing Your Custom Packages

### Make Packages Discoverable

Each package needs proper `composer.json` with:
- ✅ Valid name format: `vendor/package-name`
- ✅ Type: `library`
- ✅ Autoload configuration
- ✅ Laravel service providers in `extra.laravel.providers`

### Verify Installation

```bash
cd your-laravel-project
composer install
php artisan package:discover
```

---

## 8. Migration Path When Cortex Updates

When Cortex releases official Laravel 12 support:

### 1. Compare Packages
```bash
# Extract new cortex/foundation
composer show cortex/foundation

# Compare with your custom version
diff -r packages/cortex/foundation vendor/cortex/foundation
```

### 2. Merge Changes
- Identify your customizations
- Apply them to the official package
- Test thoroughly

### 3. Migrate Back
```bash
# Remove custom packages
rm -rf packages/cortex/foundation

# Update composer.json
# Remove path repository
# Update require to use official package

composer update cortex/foundation
```

---

## 📚 Complete Example Project

For a complete working example with all packages, see:
- `packages/cortex/foundation/` - Full foundation package
- `packages/diglactic/laravel-breadcrumbs/` - Full breadcrumbs package
- `composer.json` - Updated with path repositories

---

## ✅ Checklist for Creating Custom Packages

- [ ] Create directory structure with proper namespaces
- [ ] Write valid `composer.json` with Laravel 12 compatibility
- [ ] Create service provider with `register()` and `boot()` methods
- [ ] Implement core functionality classes
- [ ] Add configuration files if needed
- [ ] Create default views/assets
- [ ] Add helper functions if appropriate
- [ ] Create basic unit tests
- [ ] Update root `composer.json` with path repositories
- [ ] Run `composer install` and test
- [ ] Verify `php artisan package:discover` works
- [ ] Test all features work as expected
- [ ] Document any changes from original packages

---

## 🚀 Next Steps

1. **Create cortex-foundation** using Step 1 template
2. **Create custom-breadcrumbs** using Step 2 template
3. **Update root composer.json** using Step 3
4. **Test everything** works
5. **Add more packages** as needed
6. **Monitor Cortex GitHub** for official Laravel 12 support

---

**Questions?** Refer to `CORTEX_LARAVEL12_COMPATIBILITY.md` for detailed explanation and decision matrix.
