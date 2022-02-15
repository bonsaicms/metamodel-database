<?php

namespace BonsaiCms\MetamodelDatabase\Contracts;

use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\Metamodel\Models\Attribute;
use BonsaiCms\Metamodel\Models\Relationship;

interface DatabaseManagerContract
{
    /*
     * Entity
     */

    function createEntity(Entity $entity): self;

    function updateEntity(Entity $entity): self;

    function deleteEntity(Entity $entity): self;

    /*
     * Attribute
     */

    function createAttribute(Attribute $attribute): self;

    function updateAttribute(Attribute $attribute): self;

    function deleteAttribute(Attribute $attribute): self;

    /*
     * Relationship
     */

    function createRelationship(Relationship $relationship): self;

    function updateRelationship(Relationship $relationship): self;

    function deleteRelationship(Relationship $relationship): self;
}
