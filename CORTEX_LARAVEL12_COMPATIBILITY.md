# Laravel 12 Upgrade - Cortex Compatibility Guide

**Status:** Cortex packages do not yet support Laravel 12 in stable releases
**Created:** January 28, 2026
**Project:** Cortex Laravel 11 → Custom Laravel 12 Build

---

## 🔴 The Problem

### Current State
- Your **upgrade scripts work perfectly** for migrating local packages
- **`composer update` fails** due to dependency version conflicts
- **Cortex packages** (cortex/foundation, cortex/auth, cortex/categories, etc.) only support Laravel up to 11.x
- **Cortex's dependencies** also have tight version constraints that prevent Laravel 11+ installations

### Root Cause
```
cortex/* packages (max: Laravel 11)
    ↓ requires
rinvex/* packages (some updated to Laravel 12, but dev-master only)
    ↓ requires
diglactic/laravel-breadcrumbs (max: Laravel 10)
    ↓ requires
older Laravel versions
    ↓ CONFLICT with Laravel 12
```

### Error Chain
When trying to resolve dependencies:
```
cortex/tags → requires diglactic/laravel-breadcrumbs ^8.1.0
diglactic/laravel-breadcrumbs ^8.1.0 → requires laravel/framework ^6.0 || ^7.0 || ^8.0 || ^9.0 || ^10.0
laravel/framework ^12.0 → INCOMPATIBLE
```

---

## ✅ Solutions Available

### Solution 1: Stay on Laravel 11 (Safest)
**Pros:** Everything works, no custom code needed, fully supported
**Cons:** Miss out on Laravel 12 features
**Effort:** Minimal (just revert framework version)

### Solution 2: Create Custom Cortex Packages (Recommended)
**Pros:** Get Laravel 12 support, maintain Cortex features, fully customizable
**Cons:** Requires ongoing maintenance
**Effort:** Medium (2-4 hours for basic setup)

### Solution 3: Use Cortex dev-master (Risky)
**Pros:** May work if dev versions are stable enough
**Cons:** Unstable, no support, breaking changes likely
**Effort:** Low initial, high risk

### Solution 4: Migrate Away from Cortex (Major Refactor)
**Pros:** Full control, use modern alternatives
**Cons:** Complete rewrite, high effort, time-consuming
**Effort:** Very High (weeks of work)

---

## 🛠️ Recommended: Solution 2 - Custom Cortex Packages

### Why This Approach?

1. **Maintains Your Architecture:** Keep all Cortex features and structure
2. **Keeps Code in Your Control:** No external dependency surprises
3. **Enables Laravel 12:** Get new features and performance improvements
4. **Future-Proof:** When Cortex releases official support, migrate smoothly
5. **Learning Opportunity:** Understand the framework deeply

### Implementation Steps

#### Step 1: Create Custom Package Framework
Create wrapper packages in `packages/cortex/` that:
- Implement Cortex interfaces with minimal changes
- Update deprecated Laravel code
- Fix incompatible dependencies
- Maintain backwards compatibility where possible

#### Step 2: Create Custom Replacement Packages
For packages blocking the upgrade:
- `packages/cortex-foundation/` - Custom version of cortex/foundation
- `packages/cortex-auth/` - Custom version of cortex/auth
- `packages/diglactic-breadcrumbs/` - Updated breadcrumbs for Laravel 12
- `packages/rinvex-support/` - Updated Rinvex support library

#### Step 3: Update composer.json
Replace remote package references with local ones:
```json
"repositories": [
  {"type": "path", "url": "packages/cortex/*"},
  {"type": "path", "url": "packages/diglactic/*"},
  {"type": "path", "url": "packages/rinvex/*"}
],
"require": {
  "cortex/foundation": "*@dev",
  "diglactic/laravel-breadcrumbs": "*@dev"
}
```

#### Step 4: Update Code for Laravel 12
Key changes needed:
- Replace deprecated helpers
- Update middleware signatures
- Fix service provider syntax
- Update facade usage
- Replace removed components

---

## 📋 Implementation Checklist

### Phase 1: Analysis (1 hour)
- [ ] Inventory all Cortex packages used
- [ ] Document which features are essential
- [ ] List all package dependencies
- [ ] Identify deprecated code in app/

### Phase 2: Create Stub Packages (2-3 hours)
- [ ] Create base package structures in `packages/cortex/`
- [ ] Copy source code from installed Cortex packages
- [ ] Create minimal composer.json files
- [ ] Set up autoloading correctly
- [ ] Add Laravel service provider registration

### Phase 3: Update for Laravel 12 (3-4 hours)
- [ ] Remove deprecated Laravel code
- [ ] Update middleware signatures
- [ ] Fix service providers
- [ ] Update facades and contracts
- [ ] Replace removed helpers

### Phase 4: Test & Debug (2-3 hours)
- [ ] Run composer install
- [ ] Test artisan commands
- [ ] Verify package discovery
- [ ] Test core features
- [ ] Check for runtime errors

### Phase 5: Migration (1 hour)
- [ ] Update app/modules/ to use new packages
- [ ] Update config files
- [ ] Run migrations if needed
- [ ] Clear caches

---

## 🔍 What You Need to Customize

### Cortex Packages to Create
1. **cortex-foundation**
   - Base package all others depend on
   - ~200 lines of core functionality
   - Mainly service provider registration

2. **cortex-auth** (Optional, if needed)
   - Authentication module for Cortex
   - Contains middleware and contracts
   - ~500 lines per feature

3. **cortex-categories** (Optional)
   - Category management module
   - ~300 lines per feature

### Third-Party Packages to Replace
1. **diglactic/laravel-breadcrumbs**
   - Update to support Laravel 11/12
   - Main changes: service provider, middleware
   - ~100-150 lines total

2. **Rinvex Packages** (if needed)
   - Already migrated to `packages/Rinvex/`
   - May just need version bump in composer.json

---

## 📁 Directory Structure for Custom Packages

```
packages/
├── cortex/
│   ├── foundation/
│   │   ├── src/
│   │   │   ├── Providers/
│   │   │   │   └── FoundationServiceProvider.php
│   │   │   ├── Contracts/
│   │   │   ├── Exceptions/
│   │   │   └── ...
│   │   ├── composer.json
│   │   └── README.md
│   ├── auth/
│   ├── categories/
│   └── ...
├── diglactic/
│   └── laravel-breadcrumbs/
│       ├── src/
│       ├── composer.json
│       └── README.md
└── (other packages)
```

---

## 🚀 Quick Start: Creating First Custom Package

### 1. Extract Existing Package
```bash
# Copy from vendor
cp -r vendor/cortex/foundation packages/cortex/foundation
cd packages/cortex/foundation

# Remove vendor dir if present
rm -rf vendor

# Create fresh composer.json
cat > composer.json << 'EOF'
{
    "name": "cortex/foundation",
    "description": "Cortex Foundation (Laravel 12 compatible)",
    "type": "library",
    "require": {
        "php": "^8.3",
        "laravel/framework": "^12.0"
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
    }
}
EOF
```

### 2. Update Service Provider
```php
// src/Providers/FoundationServiceProvider.php
<?php

namespace Cortex\Foundation\Providers;

use Illuminate\Support\ServiceProvider;

class FoundationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Package registration
    }

    public function boot(): void
    {
        // Package bootstrapping
    }
}
```

### 3. Update composer.json in Root
```json
{
    "repositories": [
        {
            "type": "path",
            "url": "packages/cortex/*"
        }
    ],
    "require": {
        "cortex/foundation": "*"
    }
}
```

### 4. Run Composer
```bash
composer update cortex/foundation
```

---

## ⚠️ Important Considerations

### When Creating Custom Packages

1. **Maintain Interfaces**
   - Keep the same namespace and class names
   - Implement the same interfaces
   - Preserve the same method signatures

2. **Update Laravel Code**
   - Replace `::class` with `'ClassName'` in older code
   - Update `Facade::getFacadeAccessor()` pattern
   - Fix middleware `handle()` signature changes
   - Replace removed helpers

3. **Test Thoroughly**
   - Unit tests for critical code
   - Integration tests for service providers
   - Manual testing of features
   - Check for breaking changes

4. **Document Changes**
   - Keep track of what you changed
   - Document why you made changes
   - List incompatibilities fixed
   - Note any feature removals

### Migration Path Forward

When Cortex releases official Laravel 12 support:
1. Compare your custom packages with official releases
2. Migrate features back to official packages
3. Remove your custom versions
4. Test thoroughly before deploying

---

## 📞 Support & Alternatives

### If Creating Custom Packages Seems Too Complex

**Option A: Simplify Cortex Usage**
- Remove unnecessary Cortex packages
- Keep only what you use
- Simplify dependencies
- Might be easier than upgrading

**Option B: Use Laravel 11 Longer**
- Wait for Cortex Laravel 12 support
- Continue with Laravel 11
- Plan upgrade for later
- Low risk, but delayed

**Option C: Fork Cortex**
- Create your own Cortex fork
- Update it for Laravel 12
- Maintain independently
- Full control but high effort

---

## 📊 Timeline & Effort Estimates

### Quick Win (Stay on Laravel 11)
- **Time:** 5 minutes
- **Risk:** Very Low
- **Outcome:** Everything works now

### Medium Effort (Create 1-2 Custom Packages)
- **Time:** 4-6 hours
- **Risk:** Low-Medium
- **Outcome:** Upgrade to Laravel 12 with core functionality

### Full Solution (Complete Custom Cortex)
- **Time:** 1-2 weeks
- **Risk:** Medium
- **Outcome:** Full Laravel 12 + all Cortex features

### Major Refactor (Migrate Away from Cortex)
- **Time:** 4-8 weeks
- **Risk:** High
- **Outcome:** Modern architecture, no Cortex dependency

---

## ✅ Decision Matrix

| Approach | Difficulty | Risk | Time | Outcome |
|----------|-----------|------|------|---------|
| **Stay on Laravel 11** | Easy | Very Low | 5 min | Keep current setup |
| **1-2 Custom Packages** | Medium | Low | 4-6 hrs | Partial Laravel 12 |
| **Full Custom Cortex** | Hard | Medium | 1-2 wks | Full Laravel 12 |
| **Migrate from Cortex** | Very Hard | High | 4-8 wks | Modern stack |

---

## 🎯 Recommended Next Steps

### For Time-Constrained Projects
1. **Short-term:** Stay on Laravel 11
2. **Medium-term:** Monitor Cortex GitHub for Laravel 12 support
3. **Long-term:** Upgrade when official support available

### For Custom Development Projects
1. **Immediate:** Create 1-2 critical custom packages
2. **Phase 1:** Test Laravel 12 with essential features
3. **Phase 2:** Add more custom packages as needed
4. **Phase 3:** Full migration when stable

### For New Projects
1. **Skip Cortex entirely** for new features
2. **Use modern Laravel packages** instead
3. **Keep Cortex for legacy compatibility**
4. **Gradually migrate existing features**

---

## 📚 Resources

- [Laravel 12 Upgrade Guide](https://laravel.com/docs/12.x/upgrade)
- [Cortex GitHub Issues](https://github.com/cortexpe/cortex/issues)
- [Creating Laravel Packages](https://laravel.com/docs/12.x/packages)
- [Composer Path Repositories](https://getcomposer.org/doc/05-repositories.md#path)

---

## 📝 Summary

**Your situation:**
- ✅ Upgrade scripts work perfectly
- ✅ Local packages migrated successfully
- ❌ Cortex ecosystem doesn't support Laravel 12 yet
- ⚠️ Can work around with custom packages

**Best path forward:**
1. Create custom versions of 1-2 critical Cortex packages
2. Update them for Laravel 12 compatibility
3. Test thoroughly
4. Plan to migrate to official versions later

**Timeline:**
- **Get working:** 4-6 hours for basic setup
- **Production-ready:** 1-2 weeks for comprehensive solution
- **Official support:** Check Cortex GitHub for updates

---

**Questions?** Refer to the provided custom package templates in the `CUSTOM_PACKAGE_TEMPLATES.md` file.
