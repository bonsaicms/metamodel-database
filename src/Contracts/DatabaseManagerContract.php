<?php

namespace BonsaiCms\MetamodelDatabase\Contracts;

use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\Metamodel\Models\Attribute;
use BonsaiCms\Metamodel\Models\Relationship;
use BonsaiCms\MetamodelDatabase\Exceptions\EntityMigrationAlreadyExistsException;
use BonsaiCms\MetamodelDatabase\Exceptions\RelationshipMigrationAlreadyExistsException;

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

    /*
     * Entity Migration
     */

    function deleteEntityMigration(Entity $entity, bool $markEntityAsNotMigrated = true): self;

    function regenerateEntityMigration(Entity $entity): self;

    /**
     * @throws EntityMigrationAlreadyExistsException
     */
    function generateEntityMigration(Entity $entity, bool $markEntityAsMigrated = true): self;

    function entityMigrationExists(Entity $entity): bool;

    function getEntityMigrationDirectoryPath(Entity $entity): string;

    function getEntityMigrationFilePath(Entity $entity): string;

    function getEntityMigrationName(Entity $entity): string;

    function getEntityMigrationFileName(Entity $entity): string;

    function getEntityMigrationContents(Entity $entity): string;

    function wasEntityMigrated(Entity $entity): bool;

    function markEntityAsMigrated(Entity $entity): self;

    function markEntityAsNotMigrated(Entity $entity): self;

    /*
     * Relationship Migration
     */

    function deleteRelationshipMigration(Relationship $relationship, bool $markRelationshipAsNotMigrated = true): self;

    function regenerateRelationshipMigration(Relationship $relationship): self;

    /**
     * @throws RelationshipMigrationAlreadyExistsException
     */
    function generateRelationshipMigration(Relationship $relationship, bool $markRelationshipAsMigrated = true): self;

    function relationshipMigrationExists(Relationship $relationship): bool;

    function getRelationshipMigrationDirectoryPath(Relationship $relationship): string;

    function getRelationshipMigrationFilePath(Relationship $relationship): string;

    function getRelationshipMigrationName(Relationship $relationship): string;

    function getRelationshipMigrationFileName(Relationship $relationship): string;

    function getRelationshipMigrationContents(Relationship $relationship): string;

    function wasRelationshipMigrated(Relationship $relationship): bool;

    function markRelationshipAsMigrated(Relationship $relationship): self;

    function markRelationshipAsNotMigrated(Relationship $relationship): self;
}
