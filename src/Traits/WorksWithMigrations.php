<?php

namespace BonsaiCms\MetamodelDatabase\Traits;

use BonsaiCms\Metamodel\Models\Entity;

trait WorksWithMigrations
{
    public function deleteMigration(Entity $entity): self
    {
        // TODO
    }

    public function regenerateMigration(Entity $entity): self
    {
        // TODO
    }

    public function generateMigration(Entity $entity): self
    {
        // TODO
    }

    public function migrationExists(Entity $entity): bool
    {
        // TODO
    }

    public function getMigrationDirectoryPath(Entity $entity): string
    {
        // TODO
    }

    public function getMigrationFilePath(Entity $entity): string
    {
        // TODO
    }

    public function getMigrationContents(Entity $entity): string
    {
        // TODO
    }
}
