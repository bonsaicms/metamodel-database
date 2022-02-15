<?php

namespace BonsaiCms\MetamodelDatabase\Observers;

use Illuminate\Support\Facades\Config;
use BonsaiCms\Metamodel\Models\Attribute;
use BonsaiCms\MetamodelDatabase\Contracts\SchemaManagerContract;

class AttributeObserver
{
    public function __construct(
        protected SchemaManagerContract $manager
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
    }
}
