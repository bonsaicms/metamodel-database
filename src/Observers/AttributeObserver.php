<?php

namespace BonsaiCms\MetamodelDatabase\Observers;

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
        $this->manager->createAttribute($attribute);
    }

    /**
     * Handle the Attribute "updated" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Attribute  $attribute
     * @return void
     */
    public function updated(Attribute $attribute)
    {
        $this->manager->updateAttribute($attribute);
    }

    /**
     * Handle the Attribute "deleted" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Attribute  $attribute
     * @return void
     */
    public function deleted(Attribute $attribute)
    {
        $this->manager->deleteAttribute($attribute);
    }

    /**
     * Handle the Attribute "forceDeleted" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Attribute  $attribute
     * @return void
     */
//    public function forceDeleted(Attribute $attribute)
//    {
//        dd("forceDeleted");
//    }
}
