<?php

namespace BonsaiCms\MetamodelDatabase;

use BonsaiCms\Metamodel\Models\Entity;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use BonsaiCms\Metamodel\Models\Attribute;
use BonsaiCms\Metamodel\Models\Relationship;
use Illuminate\Database\Schema\ColumnDefinition;
use BonsaiCms\MetamodelDatabase\Contracts\SchemaManagerContract;

class SchemaManager implements SchemaManagerContract
{
    /*
     * Entity
     */

    function createEntity(Entity $entity): self
    {
        Schema::create($entity->realTableName, function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        return $this;
    }

    function updateEntity(Entity $entity): self
    {
        Schema::rename(
            $entity->originalRealTableName,
            $entity->realTableName
        );

        return $this;
    }

    function deleteEntity(Entity $entity): self
    {
        Schema::drop($entity->realTableName);

        return $this;
    }

    /*
     * Attribute
     */

    function createAttribute(Attribute $attribute): self
    {
        Schema::table($attribute->entity->realTableName, function (Blueprint $table) use ($attribute) {
            $this->updateColumn($attribute, $table);
        });

        return $this;
    }

    function updateAttribute(Attribute $attribute): self
    {
        // TODO: $ composer require doctrine/dbal
        // TODO: $ composer require doctrine/dbal:^3.0 (Microsoft SQL Server)

        Schema::table($attribute->entity->realTableName, function (Blueprint $table) use ($attribute) {
            $table->renameColumn(
                from: $attribute->getOriginal('column'),
                to:   $attribute->column
            );

            $this->updateColumn($attribute, $table)->change();
        });

        return $this;
    }

    function deleteAttribute(Attribute $attribute): self
    {
        Schema::table($attribute->entity->realTableName, function (Blueprint $table) use ($attribute) {
            $table->dropColumn($attribute->column);
        });

        return $this;
    }

    protected function updateColumn(Attribute $attribute, Blueprint $table): ColumnDefinition
    {
        $column = $table->{$attribute->type}($attribute->column);

        if ($attribute->nullable) {
            $column->nullable();
        }

        if ($attribute->default) {
            $column->default($attribute->default);
        }

        return $column;
    }

    /*
     * Relationship
     */


    function createRelationship(Relationship $relationship): self
    {
        // TODO: Implement createRelationship() method.
        echo "createRelationship";

        return $this;
    }

    function updateRelationship(Relationship $relationship): self
    {
        // TODO: Implement updateRelationship() method.
        echo "updateRelationship";

        return $this;
    }

    function deleteRelationship(Relationship $relationship): self
    {
        // TODO: Implement deleteRelationship() method.
        echo "deleteRelationship";

        return $this;
    }
}
