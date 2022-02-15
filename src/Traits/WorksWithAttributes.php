<?php

namespace BonsaiCms\MetamodelDatabase\Traits;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use BonsaiCms\Metamodel\Models\Attribute;
use Illuminate\Database\Schema\ColumnDefinition;

trait WorksWithAttributes
{
    public function createAttribute(Attribute $attribute): self
    {
        Schema::table($attribute->entity->realTableName, function (Blueprint $table) use ($attribute) {
            $this->setColumn($attribute, $table, false);
        });

        return $this;
    }

    public function updateAttribute(Attribute $attribute): self
    {
        Schema::table($attribute->entity->realTableName, function (Blueprint $table) use ($attribute) {
            $this->setColumn($attribute, $table, true);
        });

        return $this;
    }

    public function deleteAttribute(Attribute $attribute): self
    {
        Schema::table($attribute->entity->realTableName, function (Blueprint $table) use ($attribute) {
            $table->dropColumn($attribute->column);
        });

        return $this;
    }

    protected function setColumn(Attribute $attribute, Blueprint $table, bool $update): ColumnDefinition
    {
        $parameters = [];

        // TODO
        if ($attribute->data_type === 'string') {
            $parameters['length'] = 255;
        }

        $column = $table->addColumn(
            type: $attribute->data_type,
            name: $attribute->getOriginal('column') ?: $attribute->column,
            parameters: $parameters,
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
}
