<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console;

use ReflectionClass;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Symfony\Component\Finder\Finder;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Console\Application as Artisan;
use Cortex\Foundation\Console\Commands\MigrateMakeCommand;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * The Artisan commands ignored by auto loading.
     *
     * @var array
     */
    protected $ignoreCommands = [
        MigrateMakeCommand::class,
    ];

    /**
     * The bootstrap classes for the application.
     *
     * @var string[]
     */
    // protected $bootstrappers = [
    //     \Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class,
    //     \Illuminate\Foundation\Bootstrap\LoadConfiguration::class,
    //     \Illuminate\Foundation\Bootstrap\HandleExceptions::class,
    //     \Illuminate\Foundation\Bootstrap\RegisterFacades::class,
    //     \Cortex\Foundation\Bootstrapers\SetRequestForConsole::class,
    //     \Illuminate\Foundation\Bootstrap\RegisterProviders::class,
    //     \Illuminate\Foundation\Bootstrap\BootProviders::class,
    // ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        foreach (['module', 'extension'] as $moduleType) {
            $resources = $this->discoverModuleResources(
                $moduleType,
                'bootstrap/schedule.php'
            );

            collect($resources)
                ->each(fn($file) => (require $file->getRealPath())($schedule));
        }
    }

    /**
     * Discover and register all the application commands.
     *
     * @return void
     */
    protected function commands()
    {
        foreach (['module', 'extension'] as $moduleType) {
            $resources = $this->discoverModuleResources(
                $moduleType,
                'src/Console/Commands',
                true,
                2
            );
            $paths = array_filter(array_unique(collect($resources)->map->getPathname()->toArray()), fn ($path) => is_dir($path));
            $configPath = config("rinvex.composer.cortex-{$moduleType}.path");

            if (empty($paths)) {
                return;
            }

            foreach ((new Finder())->in($paths)->files() as $command) {
                $command = ucwords(str_replace(
                    ['src/', '/', '.php'],
                    ['', '\\', ''],
                    Str::after($command->getRealPath(), $configPath.'/')
                ), '\\');

                if (is_subclass_of($command, Command::class) && ! (new ReflectionClass($command))->isAbstract() && ! in_array($command, $this->ignoreCommads)) {
                    Artisan::starting(function ($artisan) use ($command) {
                        $artisan->resolve($command);
                    });
                }
            }
        }
    }

    protected function discoverModuleResources(
        string $moduleType,
        string $relativePath,
        bool $directories = false,
        int $depth = 1
    ): array {
        $basePath = config("rinvex.composer.cortex-{$moduleType}.path");

        if (! $basePath || ! is_dir($basePath)) {
            return [];
        }

        $finder = new Finder();
        $finder->in($basePath)->depth("<={$depth}");

        if ($directories) {
            $finder->directories()->name(basename($relativePath));
        } else {
            $finder->files()->name(basename($relativePath));
        }

        return iterator_to_array($finder);
    }

}
