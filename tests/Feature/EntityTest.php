<?php

use Illuminate\Support\Facades\Schema;
use BonsaiCms\Metamodel\Models\Entity;

it('creates a table when an entity was created', function () {
    Entity::factory()
        ->create([
            'table' => 'some',
        ]);

    $this->assertDatabaseHas('bonsaicms_metamodel_entities', [
        'table' => 'some',
    ]);

    expect(Schema::hasTable('bonsaicms_some'))->toBeTrue();
});

it('deletes the table when the entity was deleted', function () {
    Entity::factory()
        ->create([
            'table' => 'some',
        ])
        ->delete();

    $this->assertDatabaseMissing('bonsaicms_metamodel_entities', [
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

    $this->assertDatabaseMissing('bonsaicms_metamodel_entities', [
        'table' => 'original',
    ]);
    $this->assertDatabaseHas('bonsaicms_metamodel_entities', [
        'table' => 'new',
    ]);

    expect(Schema::hasTable('bonsaicms_original'))->toBeFalse();
    expect(Schema::hasTable('bonsaicms_new'))->toBeTrue();
});

it('keeps the table name when the entity was not saved', function () {
    $entity = Entity::factory()
        ->create([
            'table' => 'original'
        ]);

    $entity->table = 'new';

    $this->assertDatabaseMissing('bonsaicms_metamodel_entities', [
        'table' => 'new',
    ]);
    $this->assertDatabaseHas('bonsaicms_metamodel_entities', [
        'table' => 'original',
    ]);

    expect(Schema::hasTable('bonsaicms_new'))->toBeFalse();
    expect(Schema::hasTable('bonsaicms_original'))->toBeTrue();
});
