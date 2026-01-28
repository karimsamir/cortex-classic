# Laravel 12 Upgrade & Local Packages Migration Scripts

This directory contains automated scripts to upgrade your Cortex Laravel project from Laravel 11 to Laravel 12, with proper handling of local Rinvex packages.

## 📋 Overview

Two scripts work together to automate the upgrade process:

1. **`upgrade-to-laravel12.php`** - Main PHP upgrade script
2. **`upgrade-laravel.sh`** - Bash wrapper for easy execution

Both scripts implement the steps defined in `laravel_12_upgrade_local_packages_migration_guide.md`.

## 🚀 Quick Start

### Using the Bash Wrapper (Recommended)

```bash
# Dry-run (recommended first!)
./upgrade-laravel.sh --dry-run

# Execute actual upgrade
./upgrade-laravel.sh
```

### Using the PHP Script Directly

```bash
# Dry-run
php upgrade-to-laravel12.php --dry-run

# Execute
php upgrade-to-laravel12.php
```

## 📋 What These Scripts Do

### Phase 1: Validate Prerequisites
- ✅ Checks PHP version (requires PHP 8.3+)
- ✅ Verifies `composer.json` exists
- ✅ Validates `vendor/rinvex` directory exists

### Phase 2: Backup Current State
- ✅ Creates timestamped backup directory in `backups/`
- ✅ Backs up `composer.json` and `composer.lock`
- ✅ Backup location: `backups/pre-upgrade-YYYYMMDDHHMMSS/`

### Phase 3: Migrate Rinvex Packages
- ✅ Detects all packages in `vendor/rinvex/`
- ✅ Copies them to `packages/Rinvex/`
- ✅ Skips if already migrated

### Phase 4: Update composer.json
- ✅ Adds `packages/Rinvex/*` to repositories (if not present)
- ✅ Detects all Rinvex packages in `packages/Rinvex/`
- ✅ Adds them to `require` section as `rinvex/*`
- ✅ Maintains JSON formatting

### Phase 5: Validate & Finalize
- ✅ Runs `composer dump-autoload`
- ✅ Validates `composer.json` with `composer validate`
- ✅ Provides next steps

## 🔍 Dry-Run Mode

**Always use `--dry-run` first** to see what will be changed:

```bash
./upgrade-laravel.sh --dry-run
```

This shows:
- What directories would be created
- What files would be copied
- How composer.json would be modified
- What commands would be executed

**No actual changes are made in dry-run mode.**

## 📁 Directory Structure After Upgrade

```
project-root/
├── app/                          # Main application (unchanged)
├── packages/
│   └── Rinvex/                   # Local Rinvex packages
│       ├── authy/
│       ├── countries/
│       ├── languages/
│       ├── laravel-auth/
│       ├── laravel-categories/
│       ├── laravel-menus/
│       ├── laravel-pages/
│       ├── laravel-tags/
│       └── laravel-tenants/
├── backups/
│   └── pre-upgrade-20260127.../  # Timestamped backup
├── composer.json                 # Updated
└── ...
```

## ⚙️ System Requirements

- **PHP 8.3+**
- **Composer 2.x**
- **Git** (optional, for version control)
- Linux/macOS (or WSL on Windows)

## 📝 Usage Examples

### Example 1: Review Changes First

```bash
# See what will happen
./upgrade-laravel.sh --dry-run

# Review the output and then run for real
./upgrade-laravel.sh
```

### Example 2: Using PHP Directly

```bash
cd /path/to/cortex
php upgrade-to-laravel12.php --dry-run
php upgrade-to-laravel12.php
```

### Example 3: Capture Output to File

```bash
./upgrade-laravel.sh --dry-run > upgrade-log-dryrun.txt 2>&1
./upgrade-laravel.sh > upgrade-log-actual.txt 2>&1
```

## ✅ Post-Upgrade Steps

After running the upgrade script:

1. **Review the changes:**
   ```bash
   git status
   git diff composer.json
   ```

2. **Update dependencies:**
   ```bash
   composer update
   ```

3. **Clear caches:**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan view:clear
   ```

4. **Discover packages:**
   ```bash
   php artisan package:discover
   ```

5. **Run migrations (if any):**
   ```bash
   php artisan migrate
   ```

6. **Test the application:**
   ```bash
   php artisan test
   ```

## 🔧 Recovery & Rollback

If something goes wrong:

### Find Your Backup
```bash
ls backups/
```

### Restore composer.json
```bash
cp backups/pre-upgrade-YYYYMMDDHHMMSS/composer.json .
cp backups/pre-upgrade-YYYYMMDDHHMMSS/composer.lock .
composer install
```

### Remove Migrated Packages (if needed)
```bash
rm -rf packages/Rinvex/
git checkout composer.json
```

## 🐛 Troubleshooting

### PHP Version Error
```
Error: PHP 8.3+ is required
```
**Solution:** Update PHP to 8.3 or higher

### composer.json Parse Error
```
Error: Failed to parse composer.json
```
**Solution:** Check `composer.json` syntax with `composer validate`

### Permission Denied
```
Permission denied: upgrade-laravel.sh
```
**Solution:** Make script executable:
```bash
chmod +x upgrade-laravel.sh
chmod +x upgrade-to-laravel12.php
```

### vendor/rinvex Not Found
```
Error: vendor/rinvex not found
```
**Solution:** Run `composer install` first

## 📚 Related Documentation

- [Laravel 12 Upgrade & Local Packages Migration Guide](laravel_12_upgrade_local_packages_migration_guide.md)
- [Laravel 12 Upgrade Guide](https://laravel.com/docs/12.x/upgrade)
- [Composer Local Repositories](https://getcomposer.org/doc/05-repositories.md#path)

## 📞 Support

If you encounter issues:

1. Check the `laravel_12_upgrade_local_packages_migration_guide.md` for detailed context
2. Review the script output (capture with `>` redirection)
3. Check your backup in `backups/` directory
4. Verify PHP version: `php --version`
5. Verify Composer: `composer --version`

## 📄 Script Specifications

### upgrade-to-laravel12.php
- **Language:** PHP 8.3+
- **Size:** ~500 lines
- **Dependencies:** None (uses only PHP built-ins)
- **Exit Codes:**
  - `0` = Success
  - `1` = Failure

### upgrade-laravel.sh
- **Language:** Bash
- **Size:** ~80 lines
- **Dependencies:** PHP, Bash 4.0+
- **Exit Codes:**
  - `0` = Success
  - `1` = Failure

## ⚖️ License

These scripts are part of the Cortex Laravel framework and follow the same license.

---

**Last Updated:** January 2026
**Compatible With:** Laravel 11 → 12, PHP 8.3+
