# Laravel 12 Upgrade Implementation Checklist

Complete these steps to upgrade your Cortex project from Laravel 11 to Laravel 12.

## Phase 1: Preparation & Custom Packages (Days 1-2)

- [ ] **Read Documentation**
  - [ ] Review `LARAVEL12_UPGRADE_STATUS.md` (problem analysis and solutions)
  - [ ] Review `CUSTOM_PACKAGE_TEMPLATES.md` (implementation guides)
  - [ ] Review `COMPOSER_UPDATE_GUIDE.md` (composer configuration)

- [ ] **Verify Environment**
  - [ ] Confirm PHP version: `php -v` (should be ^8.3)
  - [ ] Confirm Composer is installed: `composer --version`
  - [ ] Backup current project: `git commit` or `zip`

- [ ] **Verify Custom Packages Exist**
  - [ ] `packages/cortex/foundation/` exists with:
    - [ ] `composer.json`
    - [ ] `src/Providers/FoundationServiceProvider.php`
    - [ ] `src/Cortex.php`
    - [ ] `src/helpers.php`
    - [ ] `config/cortex.php`
  - [ ] `packages/diglactic/laravel-breadcrumbs/` exists with:
    - [ ] `composer.json`
    - [ ] `src/` directory structure
    - [ ] `config/breadcrumbs.php`

## Phase 2: Composer Configuration (Day 2)

- [ ] **Update Root composer.json**
  - [ ] Add path repositories for:
    - [ ] `packages/cortex/*`
    - [ ] `packages/diglactic/*`
    - [ ] `packages/rinvex/*`
  - [ ] Change `laravel/framework` to `^12.0`
  - [ ] Ensure composer.json is valid: `composer validate`

- [ ] **Clear Composer Cache**
  - [ ] Run: `composer clear-cache`
  - [ ] Run: `rm -rf vendor/` (optional, but recommended)

- [ ] **Test Dependency Resolution**
  - [ ] Run: `composer install --dry-run -vv`
  - [ ] Check for errors or conflicts
  - [ ] If errors appear, see troubleshooting section

- [ ] **Install Packages**
  - [ ] If dry-run succeeds, run: `composer install`
  - [ ] Verify no errors: `composer diagnose`

## Phase 3: Package Discovery & Verification (Day 2-3)

- [ ] **Discover Packages**
  - [ ] Run: `php artisan package:discover`
  - [ ] No errors should appear

- [ ] **Verify Cortex Installation**
  - [ ] Run: `php artisan tinker`
  - [ ] Test: `cortex('version')`
  - [ ] Test: `cortex()` returns instance
  - [ ] Test: `breadcrumbs()` returns instance
  - [ ] Exit tinker: `exit`

- [ ] **Check Service Providers**
  - [ ] View `bootstrap/providers.php` (new in Laravel 12)
  - [ ] Verify providers are auto-discovered
  - [ ] Run: `php artisan config:cache`

- [ ] **Verify Configuration**
  - [ ] Check `config/cortex.php` loads: `config('cortex')`
  - [ ] Check `config/breadcrumbs.php` loads: `config('breadcrumbs')`

## Phase 4: Code Migration (Days 3-4)

- [ ] **Run Upgrade Scripts**
  - [ ] Copy scripts to project root (already done)
  - [ ] Run: `bash upgrade-laravel.sh`
  - [ ] Or manually: `php upgrade-to-laravel12.php`
  - [ ] Review proposed changes
  - [ ] Approve and apply upgrades

- [ ] **Update Configuration Files**
  - [ ] Review `config/app.php` for deprecated keys
  - [ ] Review `config/logging.php` for updates
  - [ ] Review `config/cache.php` for updates
  - [ ] Review `config/queue.php` if using queues

- [ ] **Update Code**
  - [ ] Check for deprecated code in `app/` directory
  - [ ] Update middleware in `app/Http/Middleware/`
  - [ ] Update controllers in `app/Http/Controllers/`
  - [ ] Update models in `app/Models/`
  - [ ] Update commands in `app/Console/Commands/`

- [ ] **Update Dependencies**
  - [ ] Review `composer.json` for outdated packages
  - [ ] Update critical dependencies
  - [ ] Run: `composer update --no-dev`

## Phase 5: Testing & Validation (Days 4-5)

- [ ] **Unit Tests**
  - [ ] Run: `php artisan test`
  - [ ] Fix any failing tests
  - [ ] Run: `php artisan test --coverage` (optional)

- [ ] **Artisan Commands**
  - [ ] Run: `php artisan list` (no errors)
  - [ ] Run: `php artisan route:list` (if using routes)
  - [ ] Run: `php artisan migrate --dry-run` (if using migrations)

- [ ] **Web Testing**
  - [ ] Start dev server: `php artisan serve`
  - [ ] Navigate to http://localhost:8000
  - [ ] Test key pages load
  - [ ] Test key functionality works
  - [ ] Check browser console for errors

- [ ] **Cortex Module Testing**
  - [ ] Test cortex module loading
  - [ ] Test cortex features
  - [ ] Test breadcrumb generation
  - [ ] Test custom extensions

- [ ] **Database**
  - [ ] Review migrations for compatibility
  - [ ] Test migrations run: `php artisan migrate`
  - [ ] Verify data integrity
  - [ ] Test rollback: `php artisan migrate:rollback`

## Phase 6: Cleanup & Deployment (Day 5)

- [ ] **Code Cleanup**
  - [ ] Remove old/deprecated code
  - [ ] Update documentation
  - [ ] Update API documentation if applicable
  - [ ] Update README with version info

- [ ] **Performance**
  - [ ] Clear all caches: `php artisan cache:clear`
  - [ ] Clear all views: `php artisan view:clear`
  - [ ] Clear routes: `php artisan route:clear`
  - [ ] Optimize for production: `php artisan optimize`

- [ ] **Git Commit**
  - [ ] Stage all changes: `git add .`
  - [ ] Commit: `git commit -m "chore: upgrade to Laravel 12"`
  - [ ] Review changes: `git log --oneline -5`

- [ ] **Deployment Preparation**
  - [ ] Update `.env` files for production
  - [ ] Update deployment scripts if needed
  - [ ] Verify all environment variables are set
  - [ ] Create deployment plan

- [ ] **Deploy to Staging**
  - [ ] Follow your deployment process
  - [ ] Run migrations: `php artisan migrate`
  - [ ] Run tests in staging environment
  - [ ] Verify all features work

- [ ] **Deploy to Production**
  - [ ] Create backup
  - [ ] Follow your deployment process
  - [ ] Run migrations: `php artisan migrate`
  - [ ] Monitor for errors
  - [ ] Verify key features
  - [ ] Monitor performance

## Troubleshooting During Upgrade

### Composer Issues

**"Could not find package"**
- Verify path repositories are correctly configured
- Check package directories exist with correct names
- Run: `composer validate`

**"Could not find matching version"**
- Check version constraints in `composer.json`
- Use `*` for local packages instead of specific versions
- Run: `composer update --dry-run -vv`

**"Circular dependency"**
- Review custom package requirements
- Check for circular references between packages
- Verify each package has correct version constraints

### Laravel Issues

**"ServiceProvider not found"**
- Verify package is discovered: `php artisan package:discover`
- Check service provider path in package `composer.json`
- Verify autoload config is correct

**"Class not found"**
- Clear autoloader: `composer dump-autoload`
- Verify PSR-4 namespace in package `composer.json`
- Check class file exists in correct location

**"Config not loading"**
- Run: `php artisan config:cache`
- Verify config file in package
- Check service provider publishes config

**Routes not working**
- Run: `php artisan route:cache`
- Verify routes file exists
- Check route definitions syntax

## Important Reminders

🔴 **Critical**
- Always backup before upgrading
- Test thoroughly before production
- Keep Laravel 11 branch available for rollback
- Have deployment rollback plan

🟡 **Important**
- Read release notes for breaking changes
- Update all dependent packages
- Review deprecated APIs in your code
- Update documentation

🟢 **Nice to Have**
- Update docker images if using Docker
- Update CI/CD configuration
- Update development dependencies
- Run code quality checks

## Emergency Rollback

If something goes wrong:

```bash
# Stop application
# Restore from backup

# Or revert git changes
git reset --hard HEAD~1
git clean -fd

# Restore vendor
rm -rf vendor/
git checkout composer.lock
composer install

# Restart application
```

## Support Resources

- **Laravel 12 Docs:** https://laravel.com/docs/12
- **Upgrade Guide:** https://laravel.com/docs/12/upgrade
- **API Reference:** https://laravel.com/api/12.0
- **Package Development:** https://laravel.com/docs/12/packages

## Completion Checklist

When all phases are complete:

- [ ] All tests passing
- [ ] All manual testing done
- [ ] Production deployed successfully
- [ ] No critical errors in logs
- [ ] Performance acceptable
- [ ] User feedback positive
- [ ] Documentation updated
- [ ] Team trained on new features

**✅ Upgrade Complete!**

Celebrate! You've successfully upgraded to Laravel 12. Now you can:
- Enjoy new Laravel 12 features
- Receive security updates for Laravel 12
- Use modern PHP 8.3+ features
- Plan future improvements
