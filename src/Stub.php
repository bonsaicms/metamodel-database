<?php

namespace BonsaiCms\MetamodelDatabase;

use BonsaiCms\Support\Stubs\AbstractPhpClassStub;

class Stub extends AbstractPhpClassStub
{
    protected function resolveDefaultStubFilePath(string $stubFileName): string
    {
        return __DIR__.'/../resources/stubs/' . $stubFileName;
    }

    protected function resolveOverriddenStubFilePath(string $stubFileName): string|null
    {
        return resource_path('stubs/bonsaicms/metamodel-database/' . $stubFileName);
    }
}
