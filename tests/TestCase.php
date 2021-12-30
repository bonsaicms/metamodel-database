<?php

namespace BonsaiCms\MetamodelDatabase\Tests;

use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            \BonsaiCms\Metamodel\MetamodelServiceProvider::class,
            \BonsaiCms\MetamodelDatabase\MetamodelDatabaseServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        foreach ([
            '001_create_entities_table.php',
            '002_create_attributes_table.php',
            '003_create_relationships_table.php',
        ] as $migration) {
            (include __DIR__."/../vendor/bonsaicms/metamodel/database/migrations/{$migration}")->up();
        }
    }
}
