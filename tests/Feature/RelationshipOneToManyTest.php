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

it('creates foreign key in the right entity table when oneToMany relationship is created', function () {
    Relationship::factory()
        ->for($this->entity1, 'leftEntity')
        ->for($this->entity2, 'rightEntity')
        ->create([
            'type' => 'oneToMany',
            'right_foreign_key' => 'entity_1_id',
            'left_relationship_name' => 'entity2',
            'right_relationship_name' => 'entity1',
        ]);

    $this->assertDatabaseHas('pre_met_relationships_suf_met', [
        'type' => 'oneToMany',
        'right_foreign_key' => 'entity_1_id',
        'left_relationship_name' => 'entity2',
        'right_relationship_name' => 'entity1',
    ]);

    expect(Schema::hasColumn('pre_gen_table_2_suf_gen', 'entity_1_id'))->toBeTrue();
});

it('deletes foreign key in the right entity table when oneToMany relationship is deleted', function () {
    Relationship::factory()
        ->for($this->entity1, 'leftEntity')
        ->for($this->entity2, 'rightEntity')
        ->create([
            'type' => 'oneToMany',
            'right_foreign_key' => 'entity_1_id',
            'left_relationship_name' => 'entity2',
            'right_relationship_name' => 'entity1',
        ])
        ->delete();

    $this->assertDatabaseMissing('pre_met_relationships_suf_met', [
        'type' => 'oneToMany',
        'right_foreign_key' => 'entity_1_id',
        'left_relationship_name' => 'entity2',
        'right_relationship_name' => 'entity1',
    ]);

    expect(Schema::hasColumn('pre_gen_table_2_suf_gen', 'entity_1_id'))->toBeFalse();
});

it('updates DB schema when oneToMany relationship is updated');
