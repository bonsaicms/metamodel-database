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
use BonsaiCms\MetamodelDatabase\Contracts\SchemaManagerContract;

class MetamodelDatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register package.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/bonsaicms-metamodel-database.php', 'bonsaicms-metamodel-database'
        );

        if (Config::get('bonsaicms-metamodel-database.bind.schemaManager')) {
            $this->app->singleton(SchemaManagerContract::class, SchemaManager::class);
        }
    }

    /**
     * Bootstrap package.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/bonsaicms-metamodel-database.php' => $this->app->configPath('bonsaicms-metamodel-database.php'),
        ], 'bonsaicms-metamodel-database-config');

        if (Config::get('bonsaicms-metamodel-database.observeModels.entity')) {
            Entity::observe(EntityObserver::class);
        }

        if (Config::get('bonsaicms-metamodel-database.observeModels.attribute')) {
            Attribute::observe(AttributeObserver::class);
        }

        if (Config::get('bonsaicms-metamodel-database.observeModels.relationship')) {
            Relationship::observe(RelationshipObserver::class);
        }
    }
}