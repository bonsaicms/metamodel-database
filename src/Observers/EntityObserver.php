<?php

namespace BonsaiCms\MetamodelDatabase\Observers;

use BonsaiCms\Metamodel\Models\Entity;
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
        $this->manager->createEntity($entity);
    }

    /**
     * Handle the Entity "updated" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Entity  $entity
     * @return void
     */
    public function updated(Entity $entity)
    {
        $this->manager->updateEntity($entity);
    }

    /**
     * Handle the Entity "deleted" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Entity  $entity
     * @return void
     */
    public function deleted(Entity $entity)
    {
        $this->manager->deleteEntity($entity);
    }

    /**
     * Handle the Entity "forceDeleted" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Entity  $entity
     * @return void
     */
//    public function forceDeleted(Entity $entity)
//    {
//        dd("forceDeleted");
//    }
}
