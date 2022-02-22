<?php

namespace BonsaiCms\MetamodelDatabase\Tests;

use Illuminate\Support\Facades\File;
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
        config()->set('bonsaicms-metamodel-database.generate', [
            'migration' => [
                'entity' => [
                    'folder' => base_path('test-migrations'),
                    'fileSuffix' => '.generated.php',
                    'dependencies' => [
                        \Illuminate\Support\Facades\Schema::class,
                        \Illuminate\Database\Schema\Blueprint::class,
                        \Illuminate\Database\Migrations\Migration::class,
                    ],
                ],
                'relationship' => [
                    'folder' => base_path('test-migrations'),
                    'fileSuffix' => '.generated.php',
                    'dependencies' => [
                        \Illuminate\Support\Facades\Schema::class,
                        \Illuminate\Database\Schema\Blueprint::class,
                        \Illuminate\Database\Migrations\Migration::class,
                    ],
                ],
            ],
            'metamodelSeeder' => [
                'folder' => database_path('seeders'),
                'namespace' => 'TestApp\\Database\\Seeders',
                'parentClass' => \Something\TestCustomSeeder::class,
                'seederName' => 'TestMetamodelSeederCustomName',
                'fileSuffix' => '.generated.php',
                'dependencies' => [
                    \Test\Some\Extra\Dependency::class,
                    \Test\Illuminate\Database\Console\Seeds\WithoutModelEvents::class,
                ],
            ],
        ]);
    }

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->deleteGeneratedFiles();
    }

    /**
     * This method is called after each test.
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->deleteGeneratedFiles();
    }

    protected function deleteGeneratedFiles()
    {
        File::deleteDirectory(
            base_path('test-migrations')
        );

        $paths = [
            database_path('seeders/*.generated.php'),
        ];

        foreach($paths as $path) {
            foreach (glob($path) as $file) {
                if(is_file($file)) {
                    unlink($file);
                }
            }
        }
    }
}
