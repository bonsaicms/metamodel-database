<?php

namespace BonsaiCms\MetamodelDatabase\Traits;

use BonsaiCms\Metamodel\Models\Entity;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

trait WorksWithEntities
{
    public function createEntity(Entity $entity): self
    {
        Schema::create($entity->realTableName, function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        return $this;
    }

    public function updateEntity(Entity $entity): self
    {
        Schema::rename(
            $entity->originalRealTableName,
            $entity->realTableName
        );

        return $this;
    }

    public function deleteEntity(Entity $entity): self
    {
        Schema::drop($entity->realTableName);

        return $this;
    }
}
