<?php

namespace BonsaiCms\MetamodelDatabase\Traits;

use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\File;
use BonsaiCms\MetamodelDatabase\Stub;
use Illuminate\Support\Facades\Config;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\Metamodel\Models\Attribute;
use BonsaiCms\Metamodel\Models\Relationship;
use BonsaiCms\Support\PhpDependenciesCollection;
use BonsaiCms\Support\Stubs\Actions\SkipEmptyLines;
use BonsaiCms\Support\Stubs\Actions\TrimNewLinesFromTheEnd;
use BonsaiCms\MetamodelDatabase\Exceptions\MetamodelSeederAlreadyExistsException;

trait WorksWithMetamodelSeeder
{
    public function deleteMetamodelSeeder(): self
    {
        if ($this->metamodelSeederExists()) {
            File::delete(
                $this->getMetamodelSeederFilePath()
            );
        }

        return $this;
    }

    public function regenerateMetamodelSeeder(): self
    {
        $this->deleteMetamodelSeeder();
        $this->generateMetamodelSeeder();

        return $this;
    }

    /**
     * @throws MetamodelSeederAlreadyExistsException
     */
    public function generateMetamodelSeeder(): self
    {
        if ($this->metamodelSeederExists()) {
            throw new MetamodelSeederAlreadyExistsException();
        }

        File::ensureDirectoryExists(
            $this->getMetamodelSeederDirectoryPath()
        );

        File::put(
            path: $this->getMetamodelSeederFilePath(),
            contents: $this->getMetamodelSeederContents()
        );

        return $this;
    }

    public function metamodelSeederExists(): bool
    {
        return File::exists(
            $this->getMetamodelSeederFilePath()
        );
    }

    public function getMetamodelSeederDirectoryPath(): string
    {
        return Config::get('bonsaicms-metamodel-database.generate.metamodelSeeder.folder').'/';
    }

    public function getMetamodelSeederFilePath(): string
    {
        return $this->getMetamodelSeederDirectoryPath().
            Config::get('bonsaicms-metamodel-database.generate.metamodelSeeder.seederName').
            Config::get('bonsaicms-metamodel-database.generate.metamodelSeeder.fileSuffix');
    }

    public function getMetamodelSeederContents(): string
    {
        return Stub::make('metamodelSeeder/file', [
            'namespace' => $this->resolveMetamodelSeederNamespace(),
            'dependencies' => $this->resolveMetamodelSeederDependencies(),
            'className' => $this->resolveMetamodelSeederClassName(),
            'parentClass' => $this->resolveMetamodelSeederParentClass(),
            'traits' => $this->resolveMetamodelSeederTraits(),
            'methods' => $this->resolveMetamodelSeederMethods(),
        ]);
    }

    protected function resolveMetamodelSeederNamespace(): string
    {
        return Config::get('bonsaicms-metamodel-database.generate.metamodelSeeder.namespace');
    }

    protected function resolveMetamodelSeederDependencies(): string
    {
        $dependencies = new PhpDependenciesCollection(
            Config::get('bonsaicms-metamodel-database.generate.metamodelSeeder.dependencies')
        );

        $dependencies->push(
            Config::get('bonsaicms-metamodel-database.generate.metamodelSeeder.parentClass')
        );

        if (Entity::exists()) {
            $dependencies->push(
                Entity::class,
            );
        }

        if (Relationship::exists()) {
            $dependencies->push(
                Relationship::class,
            );
        }

        return $dependencies->toPhpUsesString(
            $this->resolveMetamodelSeederNamespace()
        );
    }

    protected function resolveMetamodelSeederClassName(): string
    {
        return Config::get('bonsaicms-metamodel-database.generate.metamodelSeeder.seederName');
    }

    protected function resolveMetamodelSeederParentClass(): string
    {
        return class_basename(
            Config::get('bonsaicms-metamodel-database.generate.metamodelSeeder.parentClass')
        );
    }

    protected function resolveMetamodelSeederTraits(): string
    {
        return Stub::make('metamodelSeeder/traits');
    }

    protected function resolveMetamodelSeederMethods(): string
    {
        return Stub::make('metamodelSeeder/methods', [
            'methodRun' => $this->resolveMetamodelSeederMethodRun(),
        ]);
    }

    protected function resolveMetamodelSeederMethodRun(): string
    {
        return Stub::make('metamodelSeeder/methodRun', [
            'entities' => $this->resolveMetamodelSeederEntities(),
            'relationships' => $this->resolveMetamodelSeederRelationships(),
        ]);
    }

    protected function resolveMetamodelSeederEntities(): string
    {
        $entities = Entity::all();

        if ($entities->isEmpty()) {
            return '//';
        }

        return
            '// Entities'.PHP_EOL.PHP_EOL.
            app(Pipeline::class)
            ->send(
                $entities
                    ->map(function (Entity $entity) {
                        return $this->resolveMetamodelSeederEntity($entity);
                    })
                    ->join(PHP_EOL)
            )
            ->through([
                TrimNewLinesFromTheEnd::class,
            ])
            ->thenReturn();
    }

    protected function resolveMetamodelSeederEntity(Entity $entity): string
    {
        $hasAttributes = $entity->attributes->isNotEmpty();

        $hasRelationships =
            $entity->leftRelationships->isNotEmpty() ||
            $entity->rightRelationships->isNotEmpty();

        $stub = $hasRelationships
            ? ($hasAttributes
                ? 'createEntityWithAttributes'
                : 'createEntity'
            )
            : 'createAnonymousEntity';

        $result = Stub::make("metamodelSeeder/{$stub}", [
            'variable' => str($entity->name)->camel(),
            'entityName' => $entity->name,
            'entityTable' => $entity->table,
        ], [
            TrimNewLinesFromTheEnd::class,
        ]);

        if ($hasAttributes) {
            $result .= Stub::make('metamodelSeeder/createAttributes', [
                'attributes' => $this->resolveMetamodelSeederEntityAttributes($entity),
            ]);
        }

        $result .= ';'.PHP_EOL;

        return $result;
    }

    protected function resolveMetamodelSeederEntityAttributes(Entity $entity): string
    {
        return app(Pipeline::class)
            ->send(
                $entity
                    ->attributes
                    ->map(function (Attribute $attribute) {
                        $jsonEncodedDefault = json_encode($attribute->default);
                        $default = (str($jsonEncodedDefault)->startsWith('"'))
                            ? "'" . $attribute->default . "'"
                            : $jsonEncodedDefault;

                        return Stub::make('metamodelSeeder/attribute', [
                            'name' => $attribute->name,
                            'column' => $attribute->column,
                            'dataType' => $attribute->data_type,
                            'default' => $default,
                            'nullable' => $attribute->nullable ? 'true' : 'false',
                        ]);
                    })
                    ->join(PHP_EOL)
            )
            ->through([
                SkipEmptyLines::class,
                TrimNewLinesFromTheEnd::class,
            ])
            ->thenReturn();
    }

    protected function resolveMetamodelSeederRelationships(): string
    {
        $relationships = Relationship::all();

        if ($relationships->isEmpty()) {
            return '';
        }

        return
            PHP_EOL.PHP_EOL.'// Relationships'.PHP_EOL.PHP_EOL.
            app(Pipeline::class)
            ->send(
                $relationships
                    ->map(function (Relationship $relationships) {
                        return $this->resolveMetamodelSeederRelationship($relationships);
                    })
                    ->join(PHP_EOL)
            )
            ->through([
                TrimNewLinesFromTheEnd::class,
            ])
            ->thenReturn();
    }

    protected function resolveMetamodelSeederRelationship(Relationship $relationship): string
    {
        $stub = 'create'.str($relationship->cardinality)->ucfirst().'Relationship';

        return Stub::make("metamodelSeeder/$stub", [
            'pivotTable' => $relationship->pivot_table,
            'leftForeignKey' => $relationship->left_foreign_key,
            'rightForeignKey' => $relationship->right_foreign_key,
            'leftRelationshipName' => $relationship->left_relationship_name,
            'rightRelationshipName' => $relationship->right_relationship_name,
            'leftEntityVariable' => str($relationship->leftEntity->name)->camel(),
            'rightEntityVariable' => str($relationship->rightEntity->name)->camel(),
        ]);
    }
}
