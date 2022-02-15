<?php

use Illuminate\Support\Facades\Schema;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\Metamodel\Models\Attribute;
use BonsaiCms\Metamodel\Models\Relationship;

beforeEach(function () {
    for ($i = 1; $i <= 2; $i++) {
        $this->{"entity{$i}"} = Entity::factory()
            ->create([
                'table' => "table_{$i}",
            ]);
    }
});

it('creates a pivot table when manyToMany relationship is created', function () {
    Relationship::factory()
        ->for($this->entity1, 'leftEntity')
        ->for($this->entity2, 'rightEntity')
        ->create([
            'cardinality' => 'manyToMany',
            'pivot_table' => 'pivot1',
            'left_foreign_key' => 'entity_1_id',
            'right_foreign_key' => 'entity_2_id',
            'left_relationship_name' => 'entity2s',
            'right_relationship_name' => 'entity1s',
        ]);

    expect(Schema::hasTable('pre_gen_pivot1_suf_gen'))->toBeTrue();
    expect(Schema::hasColumn('pre_gen_pivot1_suf_gen', 'entity_1_id'))->toBeTrue();
    expect(Schema::hasColumn('pre_gen_pivot1_suf_gen', 'entity_2_id'))->toBeTrue();
});

it('drops a pivot table when manyToMany relationship is deleted', function () {
    Relationship::factory()
        ->for($this->entity1, 'leftEntity')
        ->for($this->entity2, 'rightEntity')
        ->create([
            'cardinality' => 'manyToMany',
            'pivot_table' => 'pivot1',
            'left_foreign_key' => 'entity_1_id',
            'right_foreign_key' => 'entity_2_id',
            'left_relationship_name' => 'entity2s',
            'right_relationship_name' => 'entity1s',
        ])
        ->delete();

    expect(Schema::hasTable('pre_gen_pivot1_suf_gen'))->toBeFalse();
});

it('updates DB schema when manyToMany relationship is updated');
