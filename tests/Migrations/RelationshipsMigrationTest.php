<?php

use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\Metamodel\Models\Relationship;

beforeEach(function () {
    $this->blueDog = Entity::factory()
        ->create([
            'name' => 'BlueDog',
            'table' => 'blue_dogs_table_name',
            'created_at' => '01.01.2000 12:34:56'
        ]);

    $this->redCat = Entity::factory()
        ->create([
            'name' => 'RedCat',
            'table' => 'red_cats_table_name',
            'created_at' => '01.02.2000 12:34:56'
        ]);
});

it('creates a migration with oneToOne relationship', function () {
    Relationship::factory()
        ->for($this->blueDog, 'leftEntity')
        ->for($this->redCat, 'rightEntity')
        ->create([
            'cardinality' => 'oneToOne',
            'right_foreign_key' => 'blue_dog_foreign_key_id',
            'left_relationship_name' => 'redCat',
            'right_relationship_name' => 'blueDog',
            'created_at' => '01.03.2000 12:34:56'
        ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generated/migrations/blueDog.php',
        actual: base_path('test-migrations/2000_01_01_123456_create_blue_dogs_table_name_table.generated.php')
    );
    $this->assertFileEquals(
        expected: __DIR__.'/../generated/migrations/redCat.php',
        actual: base_path('test-migrations/2000_02_01_123456_create_red_cats_table_name_table.generated.php')
    );
    $this->assertFileEquals(
        expected: __DIR__.'/../generated/migrations/oneToOneRelationship.php',
        actual: base_path('test-migrations/2000_03_01_123456_add_blue_dogs_table_name_red_cats_table_name_one_to_one_relationship.generated.php')
    );
});

it('creates a migration with oneToMany relationship', function () {
    Relationship::factory()
        ->for($this->blueDog, 'leftEntity')
        ->for($this->redCat, 'rightEntity')
        ->create([
            'cardinality' => 'oneToMany',
            'right_foreign_key' => 'blue_dog_foreign_key_id',
            'left_relationship_name' => 'redCat',
            'right_relationship_name' => 'blueDog',
            'created_at' => '01.03.2000 12:34:56'
        ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generated/migrations/blueDog.php',
        actual: base_path('test-migrations/2000_01_01_123456_create_blue_dogs_table_name_table.generated.php')
    );
    $this->assertFileEquals(
        expected: __DIR__.'/../generated/migrations/redCat.php',
        actual: base_path('test-migrations/2000_02_01_123456_create_red_cats_table_name_table.generated.php')
    );
    $this->assertFileEquals(
        expected: __DIR__.'/../generated/migrations/oneToManyRelationship.php',
        actual: base_path('test-migrations/2000_03_01_123456_add_blue_dogs_table_name_red_cats_table_name_one_to_many_relationship.generated.php')
    );
});

it('creates a migration with manyToMany relationship', function () {
    Relationship::factory()
        ->for($this->blueDog, 'leftEntity')
        ->for($this->redCat, 'rightEntity')
        ->create([
            'cardinality' => 'manyToMany',
            'pivot_table' => 'blue_dog_red_cat_pivot_table_name',
            'left_foreign_key' => 'blue_dog_foreign_key_id',
            'right_foreign_key' => 'red_cat_foreign_key_id',
            'left_relationship_name' => 'redCats',
            'right_relationship_name' => 'blueDogs',
            'created_at' => '01.03.2000 12:34:56'
        ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generated/migrations/blueDog.php',
        actual: base_path('test-migrations/2000_01_01_123456_create_blue_dogs_table_name_table.generated.php')
    );
    $this->assertFileEquals(
        expected: __DIR__.'/../generated/migrations/redCat.php',
        actual: base_path('test-migrations/2000_02_01_123456_create_red_cats_table_name_table.generated.php')
    );
    $this->assertFileEquals(
        expected: __DIR__.'/../generated/migrations/manyToManyRelationship.php',
        actual: base_path('test-migrations/2000_03_01_123456_add_blue_dogs_table_name_red_cats_table_name_many_to_many_relationship.generated.php')
    );
});
