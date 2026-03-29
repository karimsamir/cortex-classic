<?php

declare(strict_types=1);

namespace Cortex\Auth\Providers;

use Cortex\Auth\Models\Manager;
use Illuminate\Support\ServiceProvider;
use Rinvex\Support\Traits\ConsoleTools;
use Cortex\Auth\Models\MemberTenantable;

class AuthTenantableServiceProvider extends ServiceProvider
{
    use ConsoleTools;

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register(): void
    {
        // Bind eloquent models to IoC container
        $this->registerModels([
            'cortex.auth.manager' => Manager::class,
            'cortex.auth.member' => MemberTenantable::class,
        ]);

        $this->app->singleton(\Cortex\Auth\Database\Seeders\CortexAuthSeeder::class, \Cortex\Auth\Database\Seeders\CortexAuthTenantableSeeder::class);
        $this->app->singleton(\Cortex\Auth\Console\Commands\MigrateCommand::class, \Cortex\Auth\Console\Commands\MigrateTenantableCommand::class);
    }
}
