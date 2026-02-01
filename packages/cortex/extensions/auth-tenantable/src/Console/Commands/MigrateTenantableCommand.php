<?php

declare(strict_types=1);

namespace Cortex\Auth\Console\Commands;

use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'cortex:migrate:auth')]
class MigrateTenantableCommand extends MigrateCommand
{
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        parent::handle();

        $path = config('cortex.auth.autoload_migrations') ?
            realpath(__DIR__.'/../../../database/migrations') :
            $this->laravel->databasePath('migrations/cortex/auth');

        if (file_exists($path)) {
            $this->call('migrate', [
                '--step' => true,
                '--path' => $path,
                '--realpath' => true,
                '--force' => $this->option('force'),
            ]);
        } else {
            $this->warn('No migrations found! Consider publish them first: <fg=green>php artisan cortex:publish:auth</>');
        }

        $this->line('');
    }
}
