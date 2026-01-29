# Laravel 12 Upgrade Plan - Cortex Project

**Start Date**: January 28, 2026
**Target**: Upgrade from Laravel 11 to Laravel 12
**Strategy**: Keep app structure unchanged, upgrade packages to Laravel 12 using local installation

---

## 📋 Overview

### What Changes
- ✅ PHP requirement: `^8.1.0` → `^8.3.0`
- ✅ Laravel framework: `^10.0.0 || ^11.0.0` → `^10.0.0 || ^11.0.0 || ^12.0.0`
- ✅ Update `composer.json` with L12 support
- ✅ Create local package copies for unsupported Rinvex packages (in `packages/rinvex/`)

### What Stays Unchanged (App Structure)
- ✅ `app/extensions/cortex/` – All files remain as-is
- ✅ `app/modules/cortex/` – All 8+ modules remain as-is
  - `auth-tenantable/`
  - `boards/`
  - `categories/`
  - `foundation/`
  - `pages/`
  - `pages-tenantable/`
  - `tags/`
  - `tenants/`

---

## 📦 Packages Strategy

Rinvex packages **lack L12 support** upstream. Solution: Create local copies in `packages/rinvex/` with L12 support added.

**Packages to handle locally**:
1. `rinvex/laravel-support`
2. `rinvex/laravel-composer`
3. `rinvex/laravel-auth`
4. `rinvex/laravel-categories`
5. `rinvex/laravel-tags`
6. `rinvex/laravel-pages`
7. `rinvex/laravel-tenants`
8. `rinvex/laravel-menus`

**Packages with L12 support** (update directly):
- `rinvex/countries` → `^9.1.0`
- `rinvex/languages` → `^7.1.0`

---

## 🚀 Step-by-Step Upgrade Process

### STEP 1: Update Root composer.json - Add Path Repositories

**File**: `/composer.json` (project root)

**Action**: Add path repositories for local packages

**Find Section**:
```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/laravel-shift/laravel-self-diagnosis.git"
    },
```

**Replace With** (add path repositories at the beginning):
```json
"repositories": [
    {
        "type": "path",
        "url": "packages/rinvex/*",
        "options": {
            "symlink": true
        }
    },
    {
        "type": "path",
        "url": "packages/cortex/*",
        "options": {
            "symlink": true
        }
    },
    {
        "type": "vcs",
        "url": "https://github.com/laravel-shift/laravel-self-diagnosis.git"
    },
```

**Expected Result**: Composer will resolve Rinvex packages from local `packages/` directory

---

### STEP 2: Copy Packages from Vendor to Local Packages Directory

**Action**: Create directory structure and copy all 8 Rinvex packages

```bash
# Create directories
mkdir -p packages/rinvex/{laravel-support,laravel-composer,laravel-auth,laravel-categories,laravel-tags,laravel-pages,laravel-tenants,laravel-menus}

# Copy packages from vendor to packages
cp -r vendor/rinvex/laravel-support/* packages/rinvex/laravel-support/
cp -r vendor/rinvex/laravel-composer/* packages/rinvex/laravel-composer/
cp -r vendor/rinvex/laravel-auth/* packages/rinvex/laravel-auth/
cp -r vendor/rinvex/laravel-categories/* packages/rinvex/laravel-categories/
cp -r vendor/rinvex/laravel-tags/* packages/rinvex/laravel-tags/
cp -r vendor/rinvex/laravel-pages/* packages/rinvex/laravel-pages/
cp -r vendor/rinvex/laravel-tenants/* packages/rinvex/laravel-tenants/
cp -r vendor/rinvex/laravel-menus/* packages/rinvex/laravel-menus/
```

**Expected Result**:
```
packages/rinvex/
├── laravel-auth/
├── laravel-categories/
├── laravel-composer/
├── laravel-menus/
├── laravel-pages/
├── laravel-support/
├── laravel-tags/
└── laravel-tenants/
```

---

### STEP 3: Update Local Package composer.json Files for L12

**Action**: Update each local package's `composer.json` to support Laravel 12

For each of the 8 packages, update:
- `"laravel/framework": "^10.0.0 || ^11.0.0"` → `"laravel/framework": "^10.0.0 || ^11.0.0 || ^12.0.0"`
- `"php": "^8.1"` → `"php": "^8.3"` (if present)

**Files to update**:
```
packages/rinvex/laravel-support/composer.json
packages/rinvex/laravel-composer/composer.json
packages/rinvex/laravel-auth/composer.json
packages/rinvex/laravel-categories/composer.json
packages/rinvex/laravel-tags/composer.json
packages/rinvex/laravel-pages/composer.json
packages/rinvex/laravel-tenants/composer.json
packages/rinvex/laravel-menus/composer.json
```

**Expected Result**: Each local package now supports Laravel 12

---

### STEP 4: Update Root composer.json - PHP & Laravel Requirements

**File**: `/composer.json` (project root)

**Action**: Update the `"require"` section

**Find**:
```json
"php": "^8.1.0",
```

**Replace With**:
```json
"php": "^8.3.0",
```

**Find**:
```json
"laravel/framework": "^10.0.0 || ^11.0.0",
```

**Replace With**:
```json
"laravel/framework": "^10.0.0 || ^11.0.0 || ^12.0.0",
```

**Expected Result**: Project now targets Laravel 12

---

### STEP 5: Update Root composer.json - Rinvex Package Versions

**File**: `/composer.json` (project root)

**Action**: Update supported Rinvex packages to L12-compatible versions

**Find**:
```json
"rinvex/countries": "^9.0.0",
"rinvex/languages": "^7.0.0",
```

**Replace With**:
```json
"rinvex/countries": "^9.1.0",
"rinvex/languages": "^7.1.0",
```

**Expected Result**: Rinvex packages updated to L12-compatible versions

---

### STEP 6: Update Root composer.json - Add Path Repositories (if not already done)

Verify the `"repositories"` section has path entries:

```json
"repositories": [
    {
        "type": "path",
        "url": "packages/rinvex/*",
        "options": {
            "symlink": true
        }
    },
    {
        "type": "path",
        "url": "packages/cortex/*",
        "options": {
            "symlink": true
        }
    },
    ...
]
```

**Expected Result**: Path repositories configured

---

### STEP 7: Run Composer Update

**Action**: Execute in terminal from project root

```bash
# Clear composer cache
composer clear-cache

# Update dependencies
composer update

# Regenerate autoloader
composer dump-autoload
```

**Expected Result**:
- Composer resolves all dependencies
- Local packages in `packages/` used instead of Packagist
- `vendor/` contains updated L12-compatible packages
- New `composer.lock` generated

---

### STEP 8: Verify Laravel 12 Installation

**Action**: Run verification commands

```bash
# Check Laravel version (should show 12.x.x)
php artisan --version

# Cache configuration
php artisan config:cache

# Check migration status
php artisan migrate:status

# List routes (first 20)
php artisan route:list | head -20
```

**Expected Result**: All commands succeed, Laravel 12 confirmed

---

### STEP 9: Run Tests

**Action**: Execute test suite

```bash
# Run all tests
vendor/bin/phpunit

# Or specific test suites
vendor/bin/phpunit tests/Unit
vendor/bin/phpunit tests/Feature
```

**Expected Result**: All tests pass with Laravel 12

---

### STEP 10: Review & Update Code (If Needed)

**Action**: Check these files for any L12 compatibility issues

**Check File 1**: `app/modules/cortex/foundation/src/Overrides/Illuminate/Foundation/Application.php`
- Verify Application bootstrap sequence

**Check File 2**: `config/app.php`
- Verify service provider loading

**Check File 3**: `app/modules/cortex/tenants/src/Models/Tenant.php`
- Verify polymorphic relationships

**Expected Result**: No breaking changes needed (app structure unchanged)

---

## ✅ Post-Upgrade Checklist

- [ ] STEP 1: Added path repositories to root `composer.json`
- [ ] STEP 2: Created `packages/rinvex/` structure and copied 8 packages
- [ ] STEP 3: Updated all 8 local package `composer.json` files for L12
- [ ] STEP 4: Updated root `composer.json` PHP requirement to `^8.3.0`
- [ ] STEP 5: Updated root `composer.json` Laravel requirement to `^12.0.0`
- [ ] STEP 6: Updated `rinvex/countries` and `rinvex/languages` versions
- [ ] STEP 7: Ran `composer clear-cache && composer update && composer dump-autoload`
- [ ] STEP 8: Verified `php artisan --version` shows Laravel 12.x.x
- [ ] STEP 9: Ran test suite and confirmed all tests pass
- [ ] STEP 10: Reviewed code for L12 compatibility (if needed)
- [ ] STEP 11: Verified app structure unchanged (`app/extensions/cortex/` and `app/modules/cortex/`)
- [ ] STEP 12: Verified `packages/rinvex/` contains 8 local packages
- [ ] STEP 13: All functionality working (multi-tenancy, auth, routes, etc.)

---

## 📁 Final Directory Structure

After completing all steps:

```
cortex/
│
├── app/
│   ├── extensions/cortex/        ✅ UNCHANGED
│   └── modules/cortex/
│       ├── auth-tenantable/      ✅ UNCHANGED
│       ├── boards/               ✅ UNCHANGED
│       ├── categories/           ✅ UNCHANGED
│       ├── foundation/           ✅ UNCHANGED
│       ├── pages/                ✅ UNCHANGED
│       ├── pages-tenantable/     ✅ UNCHANGED
│       ├── tags/                 ✅ UNCHANGED
│       └── tenants/              ✅ UNCHANGED
│
├── packages/                     ✨ NEW
│   └── rinvex/
│       ├── laravel-auth/
│       ├── laravel-categories/
│       ├── laravel-composer/
│       ├── laravel-menus/
│       ├── laravel-pages/
│       ├── laravel-support/
│       ├── laravel-tags/
│       └── laravel-tenants/
│
├── vendor/                       (Updated with L12)
├── composer.json                 (Updated)
├── composer.lock                 (Regenerated)
└── ... (all other files unchanged)
```

---

## 🔄 If You Need to Revert

To revert to Laravel 11:

1. Revert `composer.json` Laravel requirement
2. Run `composer update`
3. (Optional) Remove `packages/rinvex/` directory

---

**Status**: Ready to execute
**Last Updated**: January 29, 2026
