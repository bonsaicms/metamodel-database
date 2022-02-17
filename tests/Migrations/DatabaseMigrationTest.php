<?php

use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelDatabase\Contracts\DatabaseManagerContract;

it('generates a correct migration name', function () {
    $entity = Entity::factory()
        ->make([
            'table' => 'some_testing',
            'created_at' => '01.02.2000 12:34:56'
        ]);

    expect(
        app(DatabaseManagerContract::class)->getEntityMigrationName($entity)
    )->toBe('2000_02_01_123456_create_some_testing_table');
});

it('updates migrations database table when a new entity was created', function () {
    $this->assertDatabaseMissing('migrations', [
        'migration' => '2000_02_01_123456_create_some_testing_table',
    ]);

    Entity::factory()
        ->create([
            'table' => 'some_testing',
            'created_at' => '01.02.2000 12:34:56'
        ]);

    $this->assertDatabaseHas('migrations', [
        'migration' => '2000_02_01_123456_create_some_testing_table',
    ]);
});

it('updates migrations database table when an entity was deleted', function () {
    $entity = Entity::factory()
        ->create([
            'table' => 'some_testing',
            'created_at' => '01.02.2000 12:34:56'
        ]);

    $this->assertDatabaseHas('migrations', [
        'migration' => '2000_02_01_123456_create_some_testing_table',
    ]);

    $entity->delete();

    $this->assertDatabaseMissing('migrations', [
        'migration' => '2000_02_01_123456_create_some_testing_table',
    ]);
});
