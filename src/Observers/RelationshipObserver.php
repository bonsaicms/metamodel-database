<?php

namespace BonsaiCms\MetamodelDatabase\Observers;

use Illuminate\Support\Facades\Config;
use BonsaiCms\Metamodel\Models\Relationship;
use BonsaiCms\MetamodelDatabase\Contracts\DatabaseManagerContract;

class RelationshipObserver
{
    public function __construct(
        protected DatabaseManagerContract $manager
    ) {}

    /**
     * Handle the Relationship "created" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Relationship  $relationship
     * @return void
     */
    public function created(Relationship $relationship)
    {
        if (Config::get('bonsaicms-metamodel-database.observeModels.relationship.schema.'.__FUNCTION__)) {
            $this->manager->createRelationship($relationship);
        }

        if (Config::get('bonsaicms-metamodel-database.observeModels.relationship.migration.'.__FUNCTION__)) {
            $this->manager->regenerateRelationshipMigration($relationship);
        }

        if (Config::get('bonsaicms-metamodel-database.observeModels.relationship.metamodelSeeder.'.__FUNCTION__)) {
            $this->manager->regenerateMetamodelSeeder();
        }
    }

    /**
     * Handle the Relationship "updated" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Relationship  $relationship
     * @return void
     */
    public function updated(Relationship $relationship)
    {
        if (Config::get('bonsaicms-metamodel-database.observeModels.relationship.schema.'.__FUNCTION__)) {
            $this->manager->updateRelationship($relationship);
        }

        if (Config::get('bonsaicms-metamodel-database.observeModels.relationship.migration.'.__FUNCTION__)) {
            $this->manager->regenerateRelationshipMigration($relationship);
        }

        if (Config::get('bonsaicms-metamodel-database.observeModels.relationship.metamodelSeeder.'.__FUNCTION__)) {
            $this->manager->regenerateMetamodelSeeder();
        }
    }

    /**
     * Handle the Relationship "deleted" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Relationship  $relationship
     * @return void
     */
    public function deleted(Relationship $relationship)
    {
        if (Config::get('bonsaicms-metamodel-database.observeModels.relationship.schema.'.__FUNCTION__)) {
            $this->manager->deleteRelationship($relationship);
        }

        if (Config::get('bonsaicms-metamodel-database.observeModels.relationship.migration.'.__FUNCTION__)) {
            $this->manager->deleteRelationshipMigration($relationship);
        }

        if (Config::get('bonsaicms-metamodel-database.observeModels.relationship.metamodelSeeder.'.__FUNCTION__)) {
            $this->manager->regenerateMetamodelSeeder();
        }
    }
}
