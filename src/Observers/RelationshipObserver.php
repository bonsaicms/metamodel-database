<?php

namespace BonsaiCms\MetamodelDatabase\Observers;

use Illuminate\Support\Facades\Config;
use BonsaiCms\Metamodel\Models\Relationship;
use BonsaiCms\MetamodelDatabase\Contracts\SchemaManagerContract;

class RelationshipObserver
{
    public function __construct(
        protected SchemaManagerContract $manager
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
    }
}
