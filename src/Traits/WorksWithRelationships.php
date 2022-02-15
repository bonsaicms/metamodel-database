<?php

namespace BonsaiCms\MetamodelDatabase\Traits;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use BonsaiCms\Metamodel\Models\Relationship;

trait WorksWithRelationships
{
    public function createRelationship(Relationship $relationship): self
    {
        if ($relationship->cardinality === 'oneToOne' || $relationship->cardinality === 'oneToMany') {
            Schema::table($relationship->rightEntity->realTableName, function (Blueprint $table) use ($relationship) {
                $table->foreignId($relationship->right_foreign_key)
                    ->constrained($relationship->leftEntity->realTableName)
                    // TODO: automaticky predpokladám, že user chce aby to bolo "cascade", ale to nemusí byť vždy pravda
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
            });
        }

        if ($relationship->cardinality === 'manyToMany') {
            Schema::create($relationship->realPivotTableName, function (Blueprint $table) use ($relationship) {
                $table->foreignId($relationship->left_foreign_key)
                    ->constrained($relationship->leftEntity->realTableName)
                    // TODO: automaticky predpokladám, že user chce aby to bolo "cascade", ale to nemusí byť vždy pravda
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

                $table->foreignId($relationship->right_foreign_key)
                    ->constrained($relationship->rightEntity->realTableName)
                    // TODO: automaticky predpokladám, že user chce aby to bolo "cascade", ale to nemusí byť vždy pravda
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
            });
        }

        return $this;
    }

    public function updateRelationship(Relationship $relationship): self
    {
        // TODO: Implement updateRelationship() method.
        echo "updateRelationship";
        throw new \Exception('Not implemented yet');

        return $this;
    }

    public function deleteRelationship(Relationship $relationship): self
    {
        if ($relationship->cardinality === 'oneToOne' || $relationship->cardinality === 'oneToMany') {
            Schema::table($relationship->rightEntity->realTableName, function (Blueprint $table) use ($relationship) {
                $table->dropColumn($relationship->right_foreign_key);
            });
        }

        if ($relationship->cardinality === 'manyToMany') {
            Schema::drop($relationship->realPivotTableName);
        }

        return $this;
    }
}
