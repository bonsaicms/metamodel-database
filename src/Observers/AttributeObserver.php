<?php

namespace BonsaiCms\MetamodelDatabase\Observers;

use Illuminate\Support\Facades\Config;
use BonsaiCms\Metamodel\Models\Attribute;
use BonsaiCms\MetamodelDatabase\Contracts\DatabaseManagerContract;

class AttributeObserver
{
    public function __construct(
        protected DatabaseManagerContract $manager
    ) {}

    /**
     * Handle the Attribute "created" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Attribute  $attribute
     * @return void
     */
    public function created(Attribute $attribute)
    {
        if (Config::get('bonsaicms-metamodel-database.observeModels.attribute.schema.'.__FUNCTION__)) {
            $this->manager->createAttribute($attribute);
        }

        if (Config::get('bonsaicms-metamodel-database.observeModels.attribute.migration.'.__FUNCTION__)) {
            $this->manager->regenerateEntityMigration($attribute->entity);
        }

        if (Config::get('bonsaicms-metamodel-database.observeModels.attribute.metamodelSeeder.'.__FUNCTION__)) {
            $this->manager->regenerateMetamodelSeeder();
        }
    }

    /**
     * Handle the Attribute "updated" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Attribute  $attribute
     * @return void
     */
    public function updated(Attribute $attribute)
    {
        if (Config::get('bonsaicms-metamodel-database.observeModels.attribute.schema.'.__FUNCTION__)) {
            $this->manager->updateAttribute($attribute);
        }

        if (Config::get('bonsaicms-metamodel-database.observeModels.attribute.migration.'.__FUNCTION__)) {
            $this->manager->regenerateEntityMigration($attribute->entity);
        }

        if (Config::get('bonsaicms-metamodel-database.observeModels.attribute.metamodelSeeder.'.__FUNCTION__)) {
            $this->manager->regenerateMetamodelSeeder();
        }
    }

    /**
     * Handle the Attribute "deleted" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Attribute  $attribute
     * @return void
     */
    public function deleted(Attribute $attribute)
    {
        if (Config::get('bonsaicms-metamodel-database.observeModels.attribute.schema.'.__FUNCTION__)) {
            $this->manager->deleteAttribute($attribute);
        }

        if (Config::get('bonsaicms-metamodel-database.observeModels.attribute.migration.'.__FUNCTION__)) {
            $this->manager->regenerateEntityMigration($attribute->entity);
        }

        if (Config::get('bonsaicms-metamodel-database.observeModels.attribute.metamodelSeeder.'.__FUNCTION__)) {
            $this->manager->regenerateMetamodelSeeder();
        }
    }
}
