<?php

use Illuminate\Support\Facades\Schema;
use BonsaiCms\Metamodel\Models\Entity;

it('creates a table when an entity was created', function () {
    Entity::factory()
        ->create([
            'table' => 'some',
        ]);

    $this->assertDatabaseHas('pre_met_entities_suf_met', [
        'table' => 'some',
    ]);

    expect(Schema::hasTable('pre_gen_some_suf_gen'))->toBeTrue();
});

it('deletes the table when the entity was deleted', function () {
    Entity::factory()
        ->create([
            'table' => 'some',
        ])
        ->delete();

    $this->assertDatabaseMissing('pre_met_entities_suf_met', [
        'table' => 'some',
    ]);

    expect(Schema::hasTable('bonsaicms_some'))->toBeFalse();
});

it('renames the table when the entity was updated', function () {
    $entity = Entity::factory()
        ->create([
            'table' => 'original'
        ]);

    $entity->table = 'new';
    $entity->save();

    $this->assertDatabaseMissing('pre_met_entities_suf_met', [
        'table' => 'original',
    ]);
    $this->assertDatabaseHas('pre_met_entities_suf_met', [
        'table' => 'new',
    ]);

    expect(Schema::hasTable('pre_gen_original_suf_gen'))->toBeFalse();
    expect(Schema::hasTable('pre_gen_new_suf_gen'))->toBeTrue();
});

it('keeps the table name when the entity was not saved', function () {
    $entity = Entity::factory()
        ->create([
            'table' => 'original'
        ]);

    $entity->table = 'new';

    $this->assertDatabaseMissing('pre_met_entities_suf_met', [
        'table' => 'new',
    ]);
    $this->assertDatabaseHas('pre_met_entities_suf_met', [
        'table' => 'original',
    ]);

    expect(Schema::hasTable('pre_gen_new_suf_gen'))->toBeFalse();
    expect(Schema::hasTable('pre_gen_original_suf_gen'))->toBeTrue();
});
