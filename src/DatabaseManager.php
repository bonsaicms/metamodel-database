<?php

namespace BonsaiCms\MetamodelDatabase;

use Illuminate\Support\Facades\Config;
use BonsaiCms\MetamodelDatabase\Traits\WorksWithEntities;
use BonsaiCms\MetamodelDatabase\Traits\WorksWithAttributes;
use BonsaiCms\MetamodelDatabase\Traits\WorksWithMigrations;
use BonsaiCms\MetamodelDatabase\Traits\WorksWithRelationships;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
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
    use WorksWithMigrations;
}
