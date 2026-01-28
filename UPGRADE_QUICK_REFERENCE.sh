#!/bin/bash
# Laravel 12 Upgrade - Quick Reference Card
#
# This file documents common commands and the upgrade workflow

cat << 'EOF'

╔════════════════════════════════════════════════════════════════════════════╗
║                    Laravel 12 Upgrade Quick Reference                      ║
║                                                                            ║
║ Project: Cortex Laravel 11 → 12                                           ║
║ Created: January 2026                                                      ║
╚════════════════════════════════════════════════════════════════════════════╝


━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 1. PRE-UPGRADE CHECKLIST
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  [ ] Verify PHP version:          php --version
  [ ] Verify Composer version:     composer --version
  [ ] Commit current changes:      git add . && git commit -m "Pre-upgrade"
  [ ] Verify vendor exists:        ls -la vendor/rinvex/
  [ ] Check Laravel version:       grep laravel/framework composer.json


━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 2. UPGRADE EXECUTION (Main Steps)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  STEP 1: Dry Run (Preview changes)
  ─────────────────────────────────
    ./upgrade-laravel.sh --dry-run

    Review output and check:
    • What will be backed up
    • What packages will be migrated
    • How composer.json will change

  STEP 2: Execute Upgrade
  ──────────────────────
    ./upgrade-laravel.sh

    This will:
    ✓ Create backups in backups/pre-upgrade-YYYYMMDDHHMMSS/
    ✓ Migrate packages/Rinvex/
    ✓ Update composer.json
    ✓ Dump autoloader
    ✓ Validate composer.json

  STEP 3: Update Dependencies
  ───────────────────────────
    composer update

  STEP 4: Discover Packages
  ─────────────────────────
    php artisan package:discover

  STEP 5: Clear All Caches
  ────────────────────────
    php artisan cache:clear
    php artisan config:clear
    php artisan view:clear

  STEP 6: Run Tests
  ────────────────
    php artisan test


━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 3. VERIFY UPGRADE SUCCESS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  Check Laravel Version:
    grep laravel/framework composer.json

  Check Rinvex in Requires:
    grep "rinvex/" composer.json

  Verify Autoload:
    php artisan tinker
    >>> exit

  Check for Errors:
    php artisan list


━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 4. IF SOMETHING GOES WRONG
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  List Available Backups:
    ls -la backups/

  Restore from Backup:
    cp backups/pre-upgrade-YYYYMMDDHHMMSS/composer.json .
    cp backups/pre-upgrade-YYYYMMDDHHMMSS/composer.lock .
    composer install

  Check What Changed:
    git status
    git diff composer.json

  View Upgrade Logs:
    cat upgrade-log.txt


━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 5. USEFUL COMMANDS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  Validate composer.json:
    composer validate

  Check for deprecated code:
    composer outdated

  Show package info:
    composer show rinvex/laravel-support

  Autoload dump:
    composer dump-autoload

  Optimize autoloader:
    composer dump-autoload --optimize

  Clear Composer cache:
    composer clear-cache

  Show Laravel version:
    php artisan --version


━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 6. DIRECTORY STRUCTURE AFTER UPGRADE
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  project-root/
  ├── app/                     ← Application code (unchanged)
  ├── packages/
  │   ├── Rinvex/              ← Migrated local packages
  │   │   ├── authy/
  │   │   ├── countries/
  │   │   ├── languages/
  │   │   ├── laravel-auth/
  │   │   ├── laravel-categories/
  │   │   ├── laravel-menus/
  │   │   ├── laravel-pages/
  │   │   ├── laravel-tags/
  │   │   └── laravel-tenants/
  │   └── (other packages)
  ├── backups/
  │   └── pre-upgrade-*/       ← Timestamped backups
  ├── composer.json            ← Updated
  ├── upgrade-laravel.sh       ← Upgrade wrapper
  ├── upgrade-to-laravel12.php ← Main upgrade script
  └── ...


━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 7. SCRIPT OPTIONS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  Bash Script:
    ./upgrade-laravel.sh [OPTIONS]

    --dry-run     Show what would be done (no changes)
    --help        Show help message
    --version     Show PHP version

  PHP Script:
    php upgrade-to-laravel12.php [--dry-run]


━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 8. DOCUMENTATION
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  Complete Guide:
    laravel_12_upgrade_local_packages_migration_guide.md

  Scripts README:
    UPGRADE_SCRIPTS_README.md

  Laravel Upgrade Guide:
    https://laravel.com/docs/12.x/upgrade

  Composer Path Repository Docs:
    https://getcomposer.org/doc/05-repositories.md#path


╔════════════════════════════════════════════════════════════════════════════╗
║                     Happy Upgrading! 🚀                                    ║
╚════════════════════════════════════════════════════════════════════════════╝

EOF
