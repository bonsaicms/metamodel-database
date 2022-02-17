<?php

namespace BonsaiCms\MetamodelDatabase\Exceptions;

use Throwable;
use BonsaiCms\Metamodel\Models\Relationship;

class RelationshipMigrationAlreadyExistsException extends AbstractMigrationAlreadyExistsException
{
    public function __construct(public Relationship $relationship, $message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
