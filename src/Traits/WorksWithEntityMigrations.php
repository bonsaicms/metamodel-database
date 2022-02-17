<?php

namespace BonsaiCms\MetamodelDatabase\Traits;

use Illuminate\Support\Str;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\File;
use BonsaiCms\MetamodelDatabase\Stub;
use BonsaiCms\Metamodel\Models\Entity;
use Illuminate\Support\Facades\Config;
use BonsaiCms\Metamodel\Models\Attribute;
use BonsaiCms\Support\PhpDependenciesCollection;
use BonsaiCms\Support\Stubs\Actions\SkipEmptyLines;
use BonsaiCms\Support\Stubs\Actions\TrimNewLinesFromTheEnd;
use BonsaiCms\MetamodelDatabase\Exceptions\EntityMigrationAlreadyExistsException;

trait WorksWithEntityMigrations
{
    public function deleteEntityMigration(Entity $entity, bool $markEntityAsNotMigrated = true): self
    {
        if ($this->entityMigrationExists($entity)) {
            File::delete(
                $this->getEntityMigrationFilePath($entity)
            );

            if ($markEntityAsNotMigrated) {
                $this->markEntityAsNotMigrated($entity);
            }
        }

        return $this;
    }

    public function regenerateEntityMigration(Entity $entity): self
    {
        if ($entity->exists) {
            $markEntity = !$this->wasEntityMigrated($entity);
            $this->deleteEntityMigration($entity, $markEntity);
            $this->generateEntityMigration($entity, $markEntity);
        } else {
            $this->deleteEntityMigration($entity);
        }

        return $this;
    }

    /**
     * @throws EntityMigrationAlreadyExistsException
     */
    public function generateEntityMigration(Entity $entity, bool $markEntityAsMigrated = true): self
    {
        if ($this->entityMigrationExists($entity)) {
            throw new EntityMigrationAlreadyExistsException($entity);
        }

        File::ensureDirectoryExists(
            $this->getEntityMigrationDirectoryPath($entity)
        );

        File::put(
            path: $this->getEntityMigrationFilePath($entity),
            contents: $this->getEntityMigrationContents($entity)
        );

        if ($markEntityAsMigrated) {
            $this->markEntityAsMigrated($entity);
        }

        return $this;
    }

    public function entityMigrationExists(Entity $entity): bool
    {
        return File::exists(
            $this->getEntityMigrationFilePath($entity)
        );
    }

    public function getEntityMigrationDirectoryPath(Entity $entity): string
    {
        return Config::get('bonsaicms-metamodel-database.generate.migration.folder').'/';
    }

    public function getEntityMigrationFilePath(Entity $entity): string
    {
        return
            $this->getEntityMigrationDirectoryPath($entity).
            $this->getEntityMigrationFileName($entity);
    }

    public function getEntityMigrationName(Entity $entity): string
    {
        return $this->getMigrationFileNamePrefix($entity).
            '_create_'.
            $entity->table.
            '_table';
    }

    public function getEntityMigrationFileName(Entity $entity): string
    {
        return $this->getEntityMigrationName($entity).
            Config::get('bonsaicms-metamodel-database.generate.migration.fileSuffix');
    }

    protected function getMigrationFileNamePrefix(Entity $entity): string
    {
        return $entity->created_at->format('Y_m_d_His');
    }

    public function getEntityMigrationContents(Entity $entity): string
    {
        return Stub::make('migration/file', [
            'dependencies' => $this->resolveMigrationDependencies($entity),
            'methods' => $this->resolveMigrationMethods($entity),
        ]);
    }

    protected function resolveMigrationDependencies(Entity $entity): string
    {
        $dependencies = new PhpDependenciesCollection(
            Config::get('bonsaicms-metamodel-database.generate.migration.dependencies')
        );

        return $dependencies->toPhpUsesString('');
    }

    protected function resolveMigrationMethods(Entity $entity): string
    {
        return app(Pipeline::class)
            ->send(
                collect([
                    $this->resolveMigrationMethodUp($entity),
                    $this->resolveMigrationMethodDown($entity),
                ])
                ->join(PHP_EOL)
            )
            ->through([
                TrimNewLinesFromTheEnd::class,
            ])
            ->thenReturn();
    }

    protected function resolveMigrationMethodUp(Entity $entity): string
    {
        return Stub::make('migration/methodUp', [
            'table' => $entity->realTableName,
            'columns' => $this->resolveMigrationColumns($entity),
        ], [
            SkipEmptyLines::class,
        ]);
    }

    protected function resolveMigrationMethodDown(Entity $entity): string
    {
        return Stub::make('migration/methodDown', [
            'table' => $entity->realTableName,
        ]);
    }

    protected function resolveMigrationColumns(Entity $entity): string
    {
        return $entity->attributes()
            ->orderBy('id') // TODO
            ->get()
            ->map(function (Attribute $attribute) {
                $decorators = collect();

                if ($attribute->nullable) {
                    $decorators->push('nullable()');
                }

                if ($attribute->default !== null) {
                    $defaultValue = json_encode($attribute->default);

                    if (Str::startsWith($defaultValue, '"')) {
                        $defaultValue = '\'' . $attribute->default . '\'';
                    }

                    $decorators->push("default({$defaultValue})");
                }

                return Stub::make('migration/column', [
                    'type' => $this->resolveColumnDataType($attribute),
                    'column' => $attribute->column,
                    'arguments' => '',
                    'decorators' => $decorators->reduce(
                        static fn ($carry, $value) => $carry .= '->' . $value
                    ),
                ]);
            })
            ->join(PHP_EOL);
    }

    public function wasEntityMigrated(Entity $entity): bool
    {
        return collect($this->migrationRepository->getRan())
            ->contains(
                $this->getEntityMigrationName($entity)
            );
    }

    public function markEntityAsMigrated(Entity $entity): self
    {
        $this->migrationRepository->log(
            $this->getEntityMigrationName($entity),
            $this->migrationRepository->getNextBatchNumber()
        );

        return $this;
    }

    public function markEntityAsNotMigrated(Entity $entity): self
    {
        $this->migrationRepository->delete((object)[
            'migration' => $this->getEntityMigrationName($entity)
        ]);

        return $this;
    }
}
