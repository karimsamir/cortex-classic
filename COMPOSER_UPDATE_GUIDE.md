# Root composer.json Update Guide

This guide explains how to update the root `composer.json` to use the custom Laravel 12-compatible packages.

## Step-by-Step Instructions

### 1. Add Path Repositories

Add the following path repositories section at the beginning of the `repositories` array in your `composer.json`:

```json
{
  "repositories": [
    {
      "type": "path",
      "url": "packages/cortex/*",
      "options": {
        "symlink": true
      }
    },
    {
      "type": "path",
      "url": "packages/diglactic/*",
      "options": {
        "symlink": true
      }
    },
    {
      "type": "path",
      "url": "packages/rinvex/*",
      "options": {
        "symlink": true
      }
    }
  ]
}
```

### 2. Update Framework Requirement

Change:
```json
"laravel/framework": "^11.0"
```

To:
```json
"laravel/framework": "^12.0"
```

### 3. Update Cortex Packages

Update all cortex packages to use the custom Laravel 12-compatible versions:

```json
"require": {
  "cortex/foundation": "*@dev",
  "cortex/core": "*@dev",
  "diglactic/laravel-breadcrumbs": "*@dev"
}
```

### 4. Full Example Repository Section

Here's what your `repositories` section should look like in `composer.json`:

```json
{
  "repositories": [
    {
      "type": "path",
      "url": "packages/cortex/*",
      "options": {
        "symlink": true
      }
    },
    {
      "type": "path",
      "url": "packages/diglactic/*",
      "options": {
        "symlink": true
      }
    },
    {
      "type": "path",
      "url": "packages/rinvex/*",
      "options": {
        "symlink": true
      }
    },
    {
      "type": "composer",
      "url": "https://repo.packagist.org"
    }
  ]
}
```

## After Making Changes

### 1. Verify Configuration

```bash
# Validate composer.json syntax
composer validate
```

### 2. Test Dependency Resolution

```bash
# See what would be installed (dry-run)
composer install --dry-run -vv

# Or with lock file check
composer install --dry-run
```

### 3. Install Dependencies

```bash
# Clear composer cache first
composer clear-cache

# Install with the new configuration
composer install

# If you previously had vendor/, you may want to remove it first
rm -rf vendor/
composer install
```

### 4. Verify Installation

```bash
# Discover packages
php artisan package:discover

# Check cortex is loaded
php artisan tinker
> cortex('version')

# Check breadcrumbs
> app('breadcrumbs')
```

## Troubleshooting

### "Could not find package"

**Cause:** Path repository not set up correctly
**Solution:** Verify the path is relative to composer.json location and the directory exists

### "Could not find a matching version"

**Cause:** Custom package version constraints are wrong
**Solution:** Make sure you're using `*` or `*@dev` as version constraint for local packages

### Circular dependency error

**Cause:** Custom packages have conflicting requirements
**Solution:** Check each package's composer.json for correct version constraints

### "Package not discovered"

**Cause:** Package not installed properly
**Solution:** Run `composer clear-cache && composer install` again

## Key Files Structure

After update, your packages should look like:

```
packages/
├── cortex/
│   └── foundation/
│       ├── composer.json
│       ├── src/
│       ├── config/
│       └── README.md
├── diglactic/
│   └── laravel-breadcrumbs/
│       ├── composer.json
│       ├── src/
│       ├── config/
│       └── README.md
└── rinvex/
    └── [other rinvex packages]
```

## Important Notes

- **Path repositories take priority** over Packagist, so local packages will be used first
- **Development version constraint (`*@dev`)** may be needed for local packages with no release tags
- **Symlinks are recommended** for local development - set `"symlink": true` in path repository options
- **Cache clearing** may be needed between composer commands

## Next Steps

After updating composer.json:

1. ✅ Update root composer.json (this guide)
2. Run `composer install`
3. Run upgrade scripts to migrate to Laravel 12
4. Test all functionality
5. Deploy

## Reference

- [Composer Path Repositories](https://getcomposer.org/doc/05-repositories.md#path)
- [Package Discovery](https://laravel.com/docs/12/packages#package-discovery)
- [Service Providers](https://laravel.com/docs/12/providers)
