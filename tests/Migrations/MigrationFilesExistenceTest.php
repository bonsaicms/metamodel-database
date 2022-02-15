<?php

use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelDatabase\Contracts\DatabaseManagerContract;

it('creates a new migration file when an entity was created', function () {
    $entity = Entity::factory()
        ->make([
            'table' => 'some_testing',
            'created_at' => '01.02.2000 12:34:56'
        ]);

    $this->assertFileDoesNotExist(
        base_path('test-migrations/2000_02_01_123456_create_some_testing_table.generated.php')
    );
    expect(
        app(DatabaseManagerContract::class)->migrationExists($entity)
    )->toBeFalse();

    $entity->save();

    $this->assertFileExists(
        base_path('test-migrations/2000_02_01_123456_create_some_testing_table.generated.php')
    );
    expect(
        app(DatabaseManagerContract::class)->migrationExists($entity)
    )->toBeTrue();
});

it('deletes the migration file when an entity was deleted', function () {
    $entity = Entity::factory()
        ->create([
            'table' => 'some_testing',
            'created_at' => '01.02.2000 12:34:56'
        ]);

    $this->assertFileExists(
        base_path('test-migrations/2000_02_01_123456_create_some_testing_table.generated.php')
    );
    expect(
        app(DatabaseManagerContract::class)->migrationExists($entity)
    )->toBeTrue();

    $entity->delete();

    $this->assertFileDoesNotExist(
        base_path('test-migrations/2000_02_01_123456_create_some_testing_table.generated.php')
    );
    expect(
        app(DatabaseManagerContract::class)->migrationExists($entity)
    )->toBeFalse();
});
