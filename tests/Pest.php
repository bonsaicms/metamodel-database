<?php

use BonsaiCms\MetamodelDatabase\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

function generated_path($path) {
    return __DIR__.'/generated/'.$path;
}
