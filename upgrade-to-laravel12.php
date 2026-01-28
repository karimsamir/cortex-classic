#!/usr/bin/env php
<?php

/**
 * Laravel 12 Upgrade & Local Packages Migration Script
 *
 * This script automates the complete upgrade process as defined in:
 * laravel_12_upgrade_local_packages_migration_guide.md
 *
 * Usage: php upgrade-to-laravel12.php [--dry-run]
 */

class Laravel12UpgradeScript
{
    private string $projectRoot;
    private bool $dryRun = false;
    private array $output = [];
    private array $errors = [];

    public function __construct()
    {
        $this->projectRoot = __DIR__;
    }

    public function run(array $argv): int
    {
        $this->parseArguments($argv);

        $this->section('🚀 Laravel 12 Upgrade & Local Packages Migration');
        $this->info('Project Root: ' . $this->projectRoot);

        if ($this->dryRun) {
            $this->warning('DRY RUN MODE - No changes will be made');
        }

        try {
            // Phase 1: Validate prerequisites
            $this->phase1ValidatePrerequisites();

            // Phase 2: Backup current state
            $this->phase2BackupCurrentState();

            // Phase 3: Migrate Rinvex packages
            $this->phase3MigrateRinvexPackages();

            // Phase 4: Update composer.json
            $this->phase4UpdateComposerJson();

            // Phase 5: Validate and finalize
            $this->phase5ValidateAndFinalize();

            $this->success('✅ Upgrade completed successfully!');
            $this->displaySummary();

            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Upgrade failed: ' . $e->getMessage());
            $this->displayErrors();
            return 1;
        }
    }

    private function parseArguments(array $argv): void
    {
        foreach ($argv as $arg) {
            if ($arg === '--dry-run') {
                $this->dryRun = true;
            }
        }
    }

    /**
     * Phase 1: Validate Prerequisites
     */
    private function phase1ValidatePrerequisites(): void
    {
        $this->section('Phase 1: Validating Prerequisites');

        // Check PHP version
        $phpVersion = phpversion();
        $this->info("Current PHP Version: $phpVersion");

        if (version_compare($phpVersion, '8.3', '<')) {
            throw new \Exception("PHP 8.3+ is required (current: $phpVersion)");
        }

        // Check composer.json exists
        if (!file_exists($this->file('composer.json'))) {
            throw new \Exception('composer.json not found');
        }

        // Check if packages/Rinvex already exists
        if (is_dir($this->file('packages/Rinvex'))) {
            $this->warning('packages/Rinvex already exists. Skipping migration.');
        } else {
            $this->info('packages/Rinvex migration required');
        }

        // Check vendor/rinvex exists
        if (!is_dir($this->file('vendor/rinvex'))) {
            throw new \Exception('vendor/rinvex not found. Run composer install first.');
        }

        $this->success('✓ All prerequisites passed');
    }

    /**
     * Phase 2: Backup Current State
     */
    private function phase2BackupCurrentState(): void
    {
        $this->section('Phase 2: Backing up Current State');

        $timestamp = date('YmdHis');
        $backupDir = $this->file("backups/pre-upgrade-$timestamp");

        if (!$this->dryRun) {
            $this->mkdir($backupDir);

            // Backup composer.json and composer.lock
            $this->copy(
                $this->file('composer.json'),
                $this->file("$backupDir/composer.json")
            );

            if (file_exists($this->file('composer.lock'))) {
                $this->copy(
                    $this->file('composer.lock'),
                    $this->file("$backupDir/composer.lock")
                );
            }

            $this->info("Backup created at: $backupDir");
        } else {
            $this->info("[DRY-RUN] Would create backup at: $backupDir");
        }

        $this->success('✓ Backup completed');
    }

    /**
     * Phase 3: Migrate Rinvex Packages
     */
    private function phase3MigrateRinvexPackages(): void
    {
        $this->section('Phase 3: Migrating Rinvex Packages');

        $vendorRinvexPath = $this->file('vendor/rinvex');
        $packagesRinvexPath = $this->file('packages/Rinvex');

        if (!is_dir($vendorRinvexPath)) {
            $this->warning('vendor/rinvex not found. Skipping migration.');
            return;
        }

        if (is_dir($packagesRinvexPath) && $this->countFiles($packagesRinvexPath) > 0) {
            $this->warning('packages/Rinvex already contains packages. Skipping migration.');
            return;
        }

        // Get all rinvex packages from vendor
        $rinvexPackages = array_filter(
            scandir($vendorRinvexPath),
            fn($item) => $item !== '.' && $item !== '..' && is_dir($vendorRinvexPath . '/' . $item)
        );

        if (empty($rinvexPackages)) {
            $this->warning('No Rinvex packages found in vendor/rinvex');
            return;
        }

        if (!$this->dryRun) {
            $this->mkdir($packagesRinvexPath);
        }

        $this->info("Found " . count($rinvexPackages) . " Rinvex packages to migrate");

        foreach ($rinvexPackages as $package) {
            $sourcePackage = $vendorRinvexPath . '/' . $package;
            $destPackage = $packagesRinvexPath . '/' . $package;

            if (!$this->dryRun) {
                $this->copy($sourcePackage, $destPackage);
                $this->info("✓ Migrated: $package");
            } else {
                $this->info("[DRY-RUN] Would migrate: $package from $sourcePackage to $destPackage");
            }
        }

        $this->success('✓ Rinvex packages migrated');
    }

    /**
     * Phase 4: Update composer.json
     */
    private function phase4UpdateComposerJson(): void
    {
        $this->section('Phase 4: Updating composer.json');

        $composerPath = $this->file('composer.json');
        $composerContent = json_decode(file_get_contents($composerPath), true);

        if (!$composerContent) {
            throw new \Exception('Failed to parse composer.json');
        }

        // Ensure repositories array exists with Rinvex path
        if (!isset($composerContent['repositories'])) {
            $composerContent['repositories'] = [];
        }

        // Check if Rinvex path repository already exists
        $rinvexRepoExists = false;
        foreach ($composerContent['repositories'] as $repo) {
            if (isset($repo['type']) && $repo['type'] === 'path' &&
                isset($repo['url']) && $repo['url'] === 'packages/Rinvex/*') {
                $rinvexRepoExists = true;
                break;
            }
        }

        if (!$rinvexRepoExists) {
            array_unshift($composerContent['repositories'], [
                'type' => 'path',
                'url' => 'packages/Rinvex/*'
            ]);
            $this->info('✓ Added packages/Rinvex/* to repositories');
        }

        // Ensure require array exists
        if (!isset($composerContent['require'])) {
            $composerContent['require'] = [];
        }

        // Get rinvex packages to require
        $rinvexPackagesDir = $this->file('packages/Rinvex');
        $rinvexPackages = [];

        if (is_dir($rinvexPackagesDir)) {
            $rinvexPackages = array_filter(
                scandir($rinvexPackagesDir),
                fn($item) => $item !== '.' && $item !== '..' && is_dir($rinvexPackagesDir . '/' . $item)
            );
        }

        // Add rinvex packages to require
        foreach ($rinvexPackages as $package) {
            $key = 'rinvex/' . strtolower($this->camelToKebab($package));
            if (!isset($composerContent['require'][$key])) {
                $composerContent['require'][$key] = '*';
                $this->info("✓ Added to require: $key");
            }
        }

        if (!$this->dryRun) {
            file_put_contents(
                $composerPath,
                json_encode($composerContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n"
            );
            $this->info('✓ composer.json updated');
        } else {
            $this->info('[DRY-RUN] Would update composer.json with new repositories and requires');
        }

        $this->success('✓ composer.json configuration completed');
    }

    /**
     * Phase 5: Validate and Finalize
     */
    private function phase5ValidateAndFinalize(): void
    {
        $this->section('Phase 5: Validating and Finalizing');

        if (!$this->dryRun) {
            // Run composer dump-autoload
            $this->info('Running composer dump-autoload...');
            $this->exec('composer dump-autoload', $output);
            $this->info('✓ Autoload dumped');

            // Verify composer.json validity
            $this->info('Validating composer.json...');
            $this->exec('composer validate', $output);
            $this->info('✓ composer.json is valid');
        } else {
            $this->info('[DRY-RUN] Would run: composer dump-autoload');
            $this->info('[DRY-RUN] Would run: composer validate');
        }

        $this->success('✓ Validation completed');
    }

    /**
     * Helper Methods
     */

    private function file(string $path): string
    {
        return $this->projectRoot . '/' . ltrim($path, '/');
    }

    private function mkdir(string $path): void
    {
        if (!is_dir($path)) {
            if (!@mkdir($path, 0755, true)) {
                throw new \Exception("Failed to create directory: $path");
            }
        }
    }

    private function copy(string $source, string $dest): void
    {
        if (!file_exists($source)) {
            throw new \Exception("Source does not exist: $source");
        }

        if (is_dir($source)) {
            $this->copyDir($source, $dest);
        } else {
            $this->mkdir(dirname($dest));
            if (!@copy($source, $dest)) {
                throw new \Exception("Failed to copy: $source to $dest");
            }
        }
    }

    private function copyDir(string $source, string $dest): void
    {
        $this->mkdir($dest);

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($files as $file) {
            $target = $dest . DIRECTORY_SEPARATOR . $files->getSubPathName();

            if ($file->isDir()) {
                $this->mkdir($target);
            } else {
                $this->mkdir(dirname($target));
                if (!@copy($file->getRealPath(), $target)) {
                    throw new \Exception("Failed to copy: " . $file->getRealPath());
                }
            }
        }
    }

    private function countFiles(string $path): int
    {
        if (!is_dir($path)) {
            return 0;
        }

        $count = 0;
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($files as $file) {
            if ($file->isFile()) {
                $count++;
            }
        }

        return $count;
    }

    private function exec(string $command, &$output = null): int
    {
        $cwd = $this->projectRoot;
        $descriptorSpec = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $process = proc_open($command, $descriptorSpec, $pipes, $cwd);

        if (!is_resource($process)) {
            throw new \Exception("Failed to execute: $command");
        }

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[0]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        $returnCode = proc_close($process);

        $output = $stdout;

        if ($returnCode !== 0 && !empty($stderr)) {
            throw new \Exception("Command failed: $command\n" . $stderr);
        }

        return $returnCode;
    }

    private function camelToKebab(string $string): string
    {
        return strtolower(preg_replace('/(?<!^)(?=[A-Z])/', '-', $string));
    }

    /**
     * Output Methods
     */

    private function section(string $message): void
    {
        echo "\n\n" . str_repeat('=', 70) . "\n";
        echo $message . "\n";
        echo str_repeat('=', 70) . "\n";
    }

    private function info(string $message): void
    {
        echo "ℹ️  " . $message . "\n";
        $this->output[] = $message;
    }

    private function success(string $message): void
    {
        echo "✅ " . $message . "\n";
        $this->output[] = $message;
    }

    private function warning(string $message): void
    {
        echo "⚠️  " . $message . "\n";
        $this->output[] = $message;
    }

    private function error(string $message): void
    {
        echo "❌ " . $message . "\n";
        $this->errors[] = $message;
    }

    private function displaySummary(): void
    {
        echo "\n\n" . str_repeat('=', 70) . "\n";
        echo "Summary\n";
        echo str_repeat('=', 70) . "\n";
        echo "Total steps completed: " . count($this->output) . "\n";
        echo "\nNext steps:\n";
        echo "1. Review changes in packages/Rinvex/\n";
        echo "2. Run: composer update\n";
        echo "3. Run: php artisan package:discover\n";
        echo "4. Test the application thoroughly\n";
    }

    private function displayErrors(): void
    {
        if (empty($this->errors)) {
            return;
        }

        echo "\n\n" . str_repeat('=', 70) . "\n";
        echo "Errors\n";
        echo str_repeat('=', 70) . "\n";
        foreach ($this->errors as $error) {
            echo "• " . $error . "\n";
        }
    }
}

$script = new Laravel12UpgradeScript();
exit($script->run($argv));
