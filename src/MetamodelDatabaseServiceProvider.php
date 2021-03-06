<?php

namespace BonsaiCms\MetamodelDatabase;

use Illuminate\Support\Facades\Config;
use BonsaiCms\Metamodel\Models\Entity;
use Illuminate\Support\ServiceProvider;
use BonsaiCms\Metamodel\Models\Attribute;
use BonsaiCms\Metamodel\Models\Relationship;
use BonsaiCms\MetamodelDatabase\Observers\EntityObserver;
use BonsaiCms\MetamodelDatabase\Observers\AttributeObserver;
use BonsaiCms\MetamodelDatabase\Observers\RelationshipObserver;
use BonsaiCms\MetamodelDatabase\Contracts\DatabaseManagerContract;

class MetamodelDatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register package.
     *
     * @return void
     */
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__.'/../config/bonsaicms-metamodel-database.php',
            'bonsaicms-metamodel-database'
        );

        // Bind implementation
        if (Config::get('bonsaicms-metamodel-database.bind.schemaManager')) {
            $this->app->singleton(DatabaseManagerContract::class, DatabaseManager::class);
        }
    }

    /**
     * Bootstrap package.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config
        $this->publishes([
            __DIR__.'/../config/bonsaicms-metamodel-database.php' => $this->app->configPath('bonsaicms-metamodel-database.php'),
        ], 'bonsaicms-metamodel-database-config');

        // Publish stubs
        $this->publishes([
            __DIR__.'/../resources/stubs/' => $this->app->resourcePath('stubs/bonsaicms/metamodel-database/'),
        ], 'bonsaicms-metamodel-database-stubs');

        // Observe models
        if (Config::get('bonsaicms-metamodel-database.observeModels.entity.enabled')) {
            Entity::observe(EntityObserver::class);
        }

        if (Config::get('bonsaicms-metamodel-database.observeModels.attribute.enabled')) {
            Attribute::observe(AttributeObserver::class);
        }

        if (Config::get('bonsaicms-metamodel-database.observeModels.relationship.enabled')) {
            Relationship::observe(RelationshipObserver::class);
        }
    }
}
