<?php

namespace BonsaiCms\MetamodelDatabase\Observers;

use BonsaiCms\Metamodel\Models\Entity;
use Illuminate\Support\Facades\Config;
use BonsaiCms\MetamodelDatabase\Contracts\SchemaManagerContract;

class EntityObserver
{
    public function __construct(
        protected SchemaManagerContract $manager
    ) {}

    /**
     * Handle the Entity "created" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Entity  $entity
     * @return void
     */
    public function created(Entity $entity)
    {
        if (Config::get('bonsaicms-metamodel-database.observeModels.entity.schema.'.__FUNCTION__)) {
            $this->manager->createEntity($entity);
        }
    }

    /**
     * Handle the Entity "updated" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Entity  $entity
     * @return void
     */
    public function updated(Entity $entity)
    {
        if (Config::get('bonsaicms-metamodel-database.observeModels.entity.schema.'.__FUNCTION__)) {
            $this->manager->updateEntity($entity);
        }
    }

    /**
     * Handle the Entity "deleted" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Entity  $entity
     * @return void
     */
    public function deleted(Entity $entity)
    {
        if (Config::get('bonsaicms-metamodel-database.observeModels.entity.schema.'.__FUNCTION__)) {
            $this->manager->deleteEntity($entity);
        }
    }
}
