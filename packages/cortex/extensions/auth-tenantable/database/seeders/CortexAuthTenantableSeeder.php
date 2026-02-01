<?php

declare(strict_types=1);

namespace Cortex\Auth\Database\Seeders;

class CortexAuthTenantableSeeder extends CortexAuthSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        parent::run();

        $abilities = [
            ['name' => 'list', 'title' => 'List managers', 'entity_type' => 'manager'],
            ['name' => 'view', 'title' => 'View managers', 'entity_type' => 'manager'],
            ['name' => 'import', 'title' => 'Import managers', 'entity_type' => 'manager'],
            ['name' => 'export', 'title' => 'Export managers', 'entity_type' => 'manager'],
            ['name' => 'create', 'title' => 'Create managers', 'entity_type' => 'manager'],
            ['name' => 'update', 'title' => 'Update managers', 'entity_type' => 'manager'],
            ['name' => 'delete', 'title' => 'Delete managers', 'entity_type' => 'manager'],
            ['name' => 'audit', 'title' => 'Audit managers', 'entity_type' => 'manager'],
        ];

        collect($abilities)->each(function (array $ability) {
            app('cortex.auth.ability')->firstOrCreate([
                'name' => $ability['name'],
                'entity_type' => $ability['entity_type'],
            ], $ability);
        });
    }
}
