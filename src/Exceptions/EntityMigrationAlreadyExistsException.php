<?php

namespace BonsaiCms\MetamodelDatabase\Exceptions;

use Throwable;
use BonsaiCms\Metamodel\Models\Entity;

class EntityMigrationAlreadyExistsException extends AbstractMigrationAlreadyExistsException
{
    public function __construct(public Entity $entity, $message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
