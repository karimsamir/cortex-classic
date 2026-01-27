# Laravel 12 Upgrade & Local Packages Migration Guide

This document describes **all required steps and decisions** to upgrade the project to **Laravel 12**, while safely moving critical code into **local packages** so that future `composer update` runs do **not overwrite custom code**.

The goal is for this file to be **AI-readable and deterministic**, so automated refactors can follow it step by step.

---

## 🎯 Objectives

1. Upgrade project to **Laravel 12**
2. **Keep `app/` folder in place** and update code safely
3. Move `vendor/rinvex/*` into **local packages** to avoid overwrite
4. Ensure Composer autoloading works correctly
5. Keep upgrade repeatable and safe

---

## 📦 Target Architecture (After Upgrade)

```
project-root/
├── app/                  # Main Laravel application (unchanged location)
├── packages/
│   └── Rinvex/            # Localized vendor packages
│       ├── Subscribable/
│       ├── Attributes/
│       └── ...
├── bootstrap/
├── config/
├── routes/
├── composer.json
└── composer.lock
```

---

## 🧱 Phase 1: Prepare Laravel 12 Upgrade

### 1.1 Update PHP Version

Laravel 12 requires:

- **PHP >= 8.3**

Update locally and in CI:

- `.env`
- Docker / server config

---

### 1.2 Update Laravel Framework

In `composer.json`:

```json
"require": {
  "laravel/framework": "^12.0"
}
```

Then run:

```bash
composer update laravel/framework --with-all-dependencies
```

⚠️ Do **NOT** refactor code yet.

---

## 🧱 Phase 2: Preserve `app/` Folder (No Migration)

### 2.1 Strategy Change

The `app/` folder **will NOT be moved** into `packages/`.

Instead:

- `app/` remains the main application layer
- Code inside `app/` is **updated in-place** to be compatible with Laravel 12
- Composer updates are considered safe for `app/` (Laravel does not overwrite it)

This avoids unnecessary complexity and keeps Laravel conventions intact.

---

### 2.2 What Stays in `app/`

All existing folders remain:

- `app/Models`
- `app/Services`
- `app/Http`
- `app/Providers`
- `app/Console`

Only **code updates and refactors** are applied (no relocation).

---

### 2.3 Required Code Updates in `app/`

AI MUST apply the following rules:

- Update method signatures to PHP 8.3
- Fix deprecated helpers removed in Laravel 12
- Replace removed facades / contracts
- Ensure strict typing where required
- Update middleware constructors

No namespace changes are allowed in this phase.

---

### 2.4 Service Providers Validation

Ensure all providers in `app/Providers`:

```php
class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}
    public function boot(): void {}
}
```

---

Run after changes:

```bash
composer dump-autoload
```

---

## 🧱 Phase 3: Migrate Rinvex Vendor Packages

### 3.1 Why This Is Required

`vendor/rinvex/*` is **overwritten on every composer update**.

We migrate them to:

```
packages/Rinvex/*
```

---

### 3.2 Copy Rinvex Packages

Example:

```bash
mkdir -p packages/Rinvex
cp -R vendor/rinvex/* packages/Rinvex/
```

---

### 3.3 Update Rinvex Namespaces (Optional)

If customization is needed:

| Old Namespace | New Namespace |
|----|----|
| `Rinvex\\` | `Rinvex\\Local\\` |

⚠️ Optional — only if avoiding collision is required.

---

### 3.4 Update Root composer.json

Remove original:

```json
"rinvex/*": "^x.y"
```

Add local versions:

```json
"repositories": [
  {
    "type": "path",
    "url": "packages/Rinvex/*"
  }
]
```

Require explicitly:

```json
"require": {
  "rinvex/subscribable": "*",
  "rinvex/attributes": "*"
}
```

---

### 3.5 Verify Autoloading

```bash
composer dump-autoload
php artisan package:discover
```

---

## 🧱 Phase 4: Laravel 12 Code Adjustments

### 4.1 Removed / Changed Features

Laravel 12:

- No legacy route caching
- Updated middleware signatures
- Strict types encouraged

AI MUST:

- Fix deprecated helpers
- Replace removed facades
- Update middleware constructors

---

### 4.2 Service Providers

Ensure providers moved into packages:

```php
class CoreServiceProvider extends ServiceProvider
{
    public function register(): void {}
    public function boot(): void {}
}
```

---

## 🧱 Phase 5: Validation Checklist

- [ ] Laravel boots
- [ ] `composer update` does NOT overwrite logic
- [ ] All Rinvex features still work
- [ ] Autoload passes
- [ ] Tests pass

---

## 🧠 AI Execution Rules

When applying this document:

1. **Do NOT move or rename `app/`**
2. **Do NOT modify vendor/**
3. **All Rinvex customizations go into packages/Rinvex**
4. **No inline hacks**
5. **PSR-4 only**
6. **Laravel 12 conventions only**

---

## 📌 Notes

- This document is source of truth
- Any deviation must be documented
- All refactors must be reversible

---

✅ End of guide
