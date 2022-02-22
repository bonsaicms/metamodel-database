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
        ]);

        Entity::create([
            'name' => 'RedCat',
            'table' => 'redCats',
        ]);
    }
}
