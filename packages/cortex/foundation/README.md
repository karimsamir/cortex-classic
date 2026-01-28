# Cortex Foundation (Custom Laravel 12 Build)

Custom implementation of Cortex Foundation compatible with Laravel 12.

## Features

- ✅ Laravel 12 compatible
- ✅ PHP 8.3+ support
- ✅ Module system support
- ✅ Feature flags
- ✅ Configuration management
- ✅ Helper functions

## Installation

This package is already installed as a path repository. No additional installation needed.

## Usage

### Using the Cortex Instance

```php
use Cortex\Foundation\Facades\Cortex;

// Get cortex instance
$cortex = cortex();

// Get configuration
$version = cortex('version');

// Check features
if (cortex()->isFeatureEnabled('multi_language')) {
    // Handle multi-language support
}
```

### Publishing Configuration

```bash
php artisan vendor:publish --tag=cortex-config
```

## Configuration

Edit `config/cortex.php` to customize:
- Module path
- Feature flags
- Database prefix
- Cache settings

## Extending

To add more functionality:

1. Create classes in `src/`
2. Register in `FoundationServiceProvider`
3. Use them in your application

## Laravel 12 Compatibility

This is a custom build created for Laravel 12 compatibility since the official Cortex packages don't yet support Laravel 12.

### Changes from Original

- Updated for Laravel 12 APIs
- Removed deprecated code
- PHP 8.3+ type hints
- Modern service provider patterns
- Removed external dependencies where possible

## License

MIT
