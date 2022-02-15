<?php

use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\Metamodel\Models\Attribute;

it('generates a correct migration for an entity without attributes', function () {
    Entity::factory()
        ->create([
            'table' => 'blue_dogs',
            'created_at' => '01.02.2000 12:34:56'
        ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generated/migrations/basic.php',
        actual: base_path('test-migrations/2000_02_01_123456_create_blue_dogs_table.generated.php')
    );
});

it('generates a correct migration for an entity with attributes', function () {
    $entity = Entity::factory()
        ->create([
            'table' => 'red_cats',
            'created_at' => '01.02.2000 12:34:56'
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_string_attribute',
            'data_type' => 'string',
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_text_attribute',
            'data_type' => 'text',
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_boolean_attribute',
            'data_type' => 'boolean',
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_integer_attribute',
            'data_type' => 'integer',
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_date_attribute',
            'data_type' => 'date',
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_time_attribute',
            'data_type' => 'time',
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_datetime_attribute',
            'data_type' => 'datetime',
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_json_attribute',
            'data_type' => 'json',
        ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generated/migrations/withAttributes.php',
        actual: base_path('test-migrations/2000_02_01_123456_create_red_cats_table.generated.php')
    );
});
