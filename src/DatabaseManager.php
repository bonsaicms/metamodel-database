<?php

namespace BonsaiCms\MetamodelDatabase;

use BonsaiCms\MetamodelDatabase\Traits\WorksWithEntities;
use BonsaiCms\MetamodelDatabase\Traits\WorksWithAttributes;
use BonsaiCms\MetamodelDatabase\Traits\WorksWithRelationships;
use BonsaiCms\MetamodelDatabase\Contracts\DatabaseManagerContract;

class DatabaseManager implements DatabaseManagerContract
{
    use WorksWithEntities;
    use WorksWithAttributes;
    use WorksWithRelationships;
}
