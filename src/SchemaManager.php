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
            $this->setColumn($attribute, $table, false);
        });

        return $this;
    }

    function updateAttribute(Attribute $attribute): self
    {
        Schema::table($attribute->entity->realTableName, function (Blueprint $table) use ($attribute) {
            $this->setColumn($attribute, $table, true);
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

    protected function setColumn(Attribute $attribute, Blueprint $table, bool $update): ColumnDefinition
    {
        $column = $table->addColumn(
            type: $attribute->type,
            name: $attribute->getOriginal('column') ?: $attribute->column
        );

        $column->nullable($attribute->nullable);
        $column->default($attribute->default);
        // TODO
//        $column->unsigned($attribute->unsigned);

        if ($update) {
            $column->change();

            if ($attribute->isDirty('column')) {
                $table->renameColumn(
                    from: $attribute->getOriginal('column'),
                    to:   $attribute->column
                );
            }
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
