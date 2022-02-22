<?php

namespace BonsaiCms\MetamodelDatabase\Observers;

use BonsaiCms\Metamodel\Models\Entity;
use Illuminate\Support\Facades\Config;
use BonsaiCms\Metamodel\Models\Relationship;
use BonsaiCms\MetamodelDatabase\Contracts\DatabaseManagerContract;

class EntityObserver
{
    public function __construct(
        protected DatabaseManagerContract $manager
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

        if (Config::get('bonsaicms-metamodel-database.observeModels.entity.migration.'.__FUNCTION__)) {
            $this->manager->regenerateEntityMigration($entity);
        }

        if (Config::get('bonsaicms-metamodel-database.observeModels.entity.metamodelSeeder.'.__FUNCTION__)) {
            $this->manager->regenerateMetamodelSeeder();
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

        if (Config::get('bonsaicms-metamodel-database.observeModels.entity.migration.'.__FUNCTION__)) {
            $this->manager->regenerateEntityMigration($entity);
        }

        if (Config::get('bonsaicms-metamodel-database.observeModels.entity.metamodelSeeder.'.__FUNCTION__)) {
            $this->manager->regenerateMetamodelSeeder();
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

        if (Config::get('bonsaicms-metamodel-database.observeModels.entity.migration.'.__FUNCTION__)) {
            $this->manager->deleteEntityMigration($entity);

            $entity->leftRelationships->each(function (Relationship $relationship) {
                $this->manager->deleteRelationshipMigration($relationship);
            });

            $entity->rightRelationships->each(function (Relationship $relationship) {
                $this->manager->deleteRelationshipMigration($relationship);
            });
        }

        if (Config::get('bonsaicms-metamodel-database.observeModels.entity.metamodelSeeder.'.__FUNCTION__)) {
            $this->manager->regenerateMetamodelSeeder();
        }
    }
}
