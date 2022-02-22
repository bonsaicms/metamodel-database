<?php

namespace TestApp\Database\Seeders;

use Something\TestCustomSeeder;
use Test\Some\Extra\Dependency;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\Metamodel\Models\Relationship;
use Test\Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TestMetamodelSeederCustomName extends TestCustomSeeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
    }
}
