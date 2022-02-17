<?php

namespace BonsaiCms\MetamodelDatabase;

use Illuminate\Support\Facades\Config;
use BonsaiCms\Metamodel\Models\Attribute;
use BonsaiCms\MetamodelDatabase\Traits\WorksWithEntities;
use BonsaiCms\MetamodelDatabase\Traits\WorksWithAttributes;
use BonsaiCms\MetamodelDatabase\Traits\WorksWithRelationships;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use BonsaiCms\MetamodelDatabase\Traits\WorksWithEntityMigrations;
use BonsaiCms\MetamodelDatabase\Contracts\DatabaseManagerContract;

class DatabaseManager implements DatabaseManagerContract
{
    public function __construct() {
        $this->migrationRepository = app(
            DatabaseMigrationRepository::class, [
                'table' => Config::get('database.migrations')
            ]
        );
    }

    use WorksWithEntities;
    use WorksWithAttributes;
    use WorksWithRelationships;
    use WorksWithEntityMigrations;

    protected function resolveColumnDataType(Attribute $attribute): string
    {
        if (in_array($attribute->data_type, ['arraylist', 'arrayhash'])) {
            return 'json';
        }

        return $attribute->data_type;
    }
}
