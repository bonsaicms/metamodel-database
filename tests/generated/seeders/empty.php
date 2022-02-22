<?php

namespace TestApp\Database\Seeders;

use Something\TestCustomSeeder;
use Test\Some\Extra\Dependency;
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
        //
    }
}
