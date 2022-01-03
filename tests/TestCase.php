<?php

namespace BonsaiCms\MetamodelDatabase\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestCase extends Orchestra
{
    use RefreshDatabase;

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
        config()->set('database.connections.testing', [
            'driver' => 'pgsql',
            'url' => null,
            'host' => '127.0.0.1',
            'port' => '5432',
            'database' => 'testing',
            'username' => 'postgres',
            'password' => 'postgres',
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ]);
        config()->set('bonsaicms-metamodel', [
            'entityTableName' => 'pre_met_entities_suf_met',
            'attributeTableName' => 'pre_met_attributes_suf_met',
            'relationshipTableName' => 'pre_met_relationships_suf_met',

            'generatedTablePrefix' => 'pre_gen_',
            'generatedTableSuffix' => '_suf_gen',
        ]);
    }
}
