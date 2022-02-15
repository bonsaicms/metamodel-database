<?php

namespace BonsaiCms\MetamodelDatabase\Contracts;

use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\Metamodel\Models\Attribute;
use BonsaiCms\Metamodel\Models\Relationship;
use BonsaiCms\MetamodelDatabase\Exceptions\MigrationAlreadyExistsException;

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
     * Migration
     */

    function deleteMigration(Entity $entity, bool $markEntityAsNotMigrated = true): self;

    function regenerateMigration(Entity $entity): self;

    /**
     * @throws MigrationAlreadyExistsException
     */
    function generateMigration(Entity $entity, bool $markEntityAsMigrated = true): self;

    function migrationExists(Entity $entity): bool;

    function getMigrationDirectoryPath(Entity $entity): string;

    function getMigrationFilePath(Entity $entity): string;

    function getMigrationName(Entity $entity): string;

    function getMigrationFileName(Entity $entity): string;

    function getMigrationContents(Entity $entity): string;

    function wasEntityMigrated(Entity $entity): bool;

    function markEntityAsMigrated(Entity $entity): self;

    function markEntityAsNotMigrated(Entity $entity): self;
}
