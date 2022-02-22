<?php

namespace TestApp\Database\Seeders;

use Something\TestCustomSeeder;
use Test\Some\Extra\Dependency;
use BonsaiCms\Metamodel\Models\Entity;
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
    }
}
