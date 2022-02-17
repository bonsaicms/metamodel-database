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

    /*
     * String
     */

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_nullable_string_attribute',
            'data_type' => 'string',
            'nullable' => true,
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_required_string_attribute',
            'data_type' => 'string',
            'nullable' => false,
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_required_string_attribute_with_default',
            'data_type' => 'string',
            'nullable' => false,
            'default' => 'some default string',
        ]);

    /*
     * Text
     */

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_nullable_text_attribute',
            'data_type' => 'text',
            'nullable' => true,
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_required_text_attribute',
            'data_type' => 'text',
            'nullable' => false,
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_required_text_attribute_with_default',
            'data_type' => 'text',
            'nullable' => false,
            'default' => 'some default text',
        ]);

    /*
     * Boolean
     */

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_nullable_boolean_attribute',
            'data_type' => 'boolean',
            'nullable' => true,
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_required_boolean_attribute',
            'data_type' => 'boolean',
            'nullable' => false,
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_required_boolean_attribute_with_default',
            'data_type' => 'boolean',
            'nullable' => false,
            'default' => true,
        ]);

    /*
     * Integer
     */

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_nullable_integer_attribute',
            'data_type' => 'integer',
            'nullable' => true,
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_required_integer_attribute',
            'data_type' => 'integer',
            'nullable' => false,
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_required_integer_attribute_with_default',
            'data_type' => 'integer',
            'nullable' => false,
            'default' => 123,
        ]);

    /*
     * Date
     */

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_nullable_date_attribute',
            'data_type' => 'date',
            'nullable' => true,
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_required_date_attribute',
            'data_type' => 'date',
            'nullable' => false,
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_required_date_attribute_with_default',
            'data_type' => 'date',
            'nullable' => false,
            'default' => '2022-02-01',
        ]);

    /*
     * Time
     */

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_nullable_time_attribute',
            'data_type' => 'time',
            'nullable' => true,
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_required_time_attribute',
            'data_type' => 'time',
            'nullable' => false,
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_required_time_attribute_with_default',
            'data_type' => 'time',
            'nullable' => false,
            'default' => '12:34:56',
        ]);

    /*
     * DateTime
     */

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_nullable_datetime_attribute',
            'data_type' => 'datetime',
            'nullable' => true,
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_required_datetime_attribute',
            'data_type' => 'datetime',
            'nullable' => false,
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_required_datetime_attribute_with_default',
            'data_type' => 'datetime',
            'nullable' => false,
            'default' => '2022-02-01 12:34:56',
        ]);

    /*
     * ArrayList
     */

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_nullable_arraylist_attribute',
            'data_type' => 'arraylist',
            'nullable' => true,
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_required_arraylist_attribute',
            'data_type' => 'arraylist',
            'nullable' => false,
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_required_arraylist_attribute_with_default',
            'data_type' => 'arraylist',
            'nullable' => false,
            'default' => '["string",123,true,4.56,{"a":"b","c":[]}]',
        ]);

    /*
     * ArrayHash
     */

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_nullable_arrayhash_attribute',
            'data_type' => 'arrayhash',
            'nullable' => true,
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_required_arrayhash_attribute',
            'data_type' => 'arrayhash',
            'nullable' => false,
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_required_arrayhash_attribute_with_default',
            'data_type' => 'arrayhash',
            'nullable' => false,
            'default' => '{"integer":123,"bool":true,"array":[1,2,3]}',
        ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generated/migrations/withAttributes.php',
        actual: base_path('test-migrations/2000_02_01_123456_create_red_cats_table.generated.php')
    );
});
