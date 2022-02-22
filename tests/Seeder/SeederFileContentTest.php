<?php

use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\Metamodel\Models\Relationship;
use BonsaiCms\MetamodelDatabase\Contracts\DatabaseManagerContract;

it('creates an empty seeder', function () {
    app(DatabaseManagerContract::class)->generateMetamodelSeeder();

    $this->assertFileEquals(
        expected: generated_path('seeders/empty.php'),
        actual: database_path('seeders/TestMetamodelSeederCustomName.generated.php')
    );
});

it('creates a seeder for entities without attributes', function () {
    Entity::create([
        'name' => 'BlueDog',
        'table' => 'blue_dogs',
    ]);

    Entity::create([
        'name' => 'RedCat',
        'table' => 'redCats',
    ]);

    $this->assertFileEquals(
        expected: generated_path('seeders/entitiesWithoutAttributes.php'),
        actual: database_path('seeders/TestMetamodelSeederCustomName.generated.php')
    );
});

it('creates a seeder for entities with attributes', function () {
    Entity::create([
        'name' => 'BlueDog',
        'table' => 'blue_dogs',
    ])->attributes()->createMany([
        [
            'name' => 'Name',
            'column' => 'name',
            'data_type' => 'string',
            'default' => 'some default name',
            'nullable' => false,
        ],
        [
            'name' => 'Age',
            'column' => 'age',
            'data_type' => 'integer',
            'default' => null,
            'nullable' => true,
        ],
    ]);

    Entity::create([
        'name' => 'RedCat',
        'table' => 'redCats',
    ])->attributes()->createMany([
        [
            'name' => 'Lives',
            'column' => 'lives',
            'data_type' => 'integer',
            'default' => 7,
            'nullable' => false,
        ],
        [
            'name' => 'Is black',
            'column' => 'is_black',
            'data_type' => 'boolean',
            'default' => null,
            'nullable' => true,
        ],
        [
            'name' => 'Some required arraylist attribute with default',
            'column' => 'some_required_arraylist_attribute_with_default',
            'data_type' => 'arraylist',
            'default' => '["string",123,true,4.56,{"a":"b","c":[]}]',
            'nullable' => false,
        ],
        [
            'name' => 'Some required arrayhash attribute with default',
            'column' => 'some_required_arrayhash_attribute_with_default',
            'data_type' => 'arrayhash',
            'default' => '{"integer":123,"bool":true,"array":[1,2,3]}',
            'nullable' => false,
        ],
    ]);

    $this->assertFileEquals(
        expected: generated_path('seeders/entitiesWithAttributes.php'),
        actual: database_path('seeders/TestMetamodelSeederCustomName.generated.php')
    );
});

it('creates a seeder for entities with relationships', function () {
    // Entities

    ($author = Entity::create([
        'name' => 'Author',
        'table' => 'authors',
    ]))->attributes()->createMany([
        [
            'name' => 'Name',
            'column' => 'name',
            'data_type' => 'string',
            'default' => null,
            'nullable' => false,
        ],
        [
            'name' => 'Email',
            'column' => 'email',
            'data_type' => 'string',
            'default' => null,
            'nullable' => true,
        ],
    ]);

    ($profile = Entity::create([
        'name' => 'Profile',
        'table' => 'profiles',
    ]))->attributes()->createMany([
        [
            'name' => 'Address',
            'column' => 'address',
            'data_type' => 'string',
            'default' => null,
            'nullable' => false,
        ],
    ]);

    ($article = Entity::create([
        'name' => 'Article',
        'table' => 'articles',
    ]))->attributes()->createMany([
        [
            'name' => 'Title',
            'column' => 'title',
            'data_type' => 'string',
            'default' => null,
            'nullable' => false,
        ],
        [
            'name' => 'Content',
            'column' => 'content',
            'data_type' => 'text',
            'default' => null,
            'nullable' => true,
        ],
    ]);

    $tag = Entity::create([
        'name' => 'Tag',
        'table' => 'tags',
    ]);

    // Relationships

    Relationship::make([
        'cardinality' => 'oneToOne',
        'right_foreign_key' => 'author_id',
        'left_relationship_name' => 'profile',
        'right_relationship_name' => 'author',
    ])
        ->leftEntity()->associate($author)
        ->rightEntity()->associate($profile)
        ->save();

    Relationship::make([
        'cardinality' => 'oneToMany',
        'right_foreign_key' => 'author_id',
        'left_relationship_name' => 'articles',
        'right_relationship_name' => 'author',
    ])
        ->leftEntity()->associate($author)
        ->rightEntity()->associate($article)
        ->save();

    Relationship::make([
        'cardinality' => 'manyToMany',
        'pivot_table' => 'article_tag',
        'left_foreign_key' => 'article_id',
        'right_foreign_key' => 'tag_id',
        'left_relationship_name' => 'tags',
        'right_relationship_name' => 'articles',
    ])
        ->leftEntity()->associate($article)
        ->rightEntity()->associate($tag)
        ->save();

    $this->assertFileEquals(
        expected: generated_path('seeders/entitiesWithRelationships.php'),
        actual: database_path('seeders/TestMetamodelSeederCustomName.generated.php')
    );
});
