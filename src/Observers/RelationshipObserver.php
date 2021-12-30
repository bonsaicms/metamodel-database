<?php

namespace BonsaiCms\MetamodelDatabase\Observers;

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
        $this->manager->createRelationship($relationship);
    }

    /**
     * Handle the Relationship "updated" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Relationship  $relationship
     * @return void
     */
    public function updated(Relationship $relationship)
    {
        $this->manager->updateRelationship($relationship);
    }

    /**
     * Handle the Relationship "deleted" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Relationship  $relationship
     * @return void
     */
    public function deleted(Relationship $relationship)
    {
        $this->manager->deleteRelationship($relationship);
    }

    /**
     * Handle the Relationship "forceDeleted" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Relationship  $relationship
     * @return void
     */
//    public function forceDeleted(Relationship $relationship)
//    {
//        dd("forceDeleted");
//    }
}
