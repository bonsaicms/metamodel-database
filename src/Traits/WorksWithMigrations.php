<?php

namespace BonsaiCms\MetamodelDatabase\Traits;

use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\File;
use BonsaiCms\MetamodelDatabase\Stub;
use BonsaiCms\Metamodel\Models\Entity;
use Illuminate\Support\Facades\Config;
use BonsaiCms\Metamodel\Models\Attribute;
use BonsaiCms\Support\PhpDependenciesCollection;
use BonsaiCms\Support\Stubs\Actions\SkipEmptyLines;
use BonsaiCms\Support\Stubs\Actions\TrimNewLinesFromTheEnd;
use BonsaiCms\MetamodelDatabase\Exceptions\MigrationAlreadyExistsException;

trait WorksWithMigrations
{
    public function deleteMigration(Entity $entity, bool $markEntityAsNotMigrated = true): self
    {
        if ($this->migrationExists($entity)) {
            File::delete(
                $this->getMigrationFilePath($entity)
            );

            if ($markEntityAsNotMigrated) {
                $this->markEntityAsNotMigrated($entity);
            }
        }

        return $this;
    }

    public function regenerateMigration(Entity $entity): self
    {
        if ($entity->exists) {
            $markEntity = !$this->wasEntityMigrated($entity);
            $this->deleteMigration($entity, $markEntity);
            $this->generateMigration($entity, $markEntity);
        } else {
            $this->deleteMigration($entity);
        }

        return $this;
    }

    /**
     * @throws MigrationAlreadyExistsException
     */
    public function generateMigration(Entity $entity, bool $markEntityAsMigrated = true): self
    {
        if ($this->migrationExists($entity)) {
            throw new MigrationAlreadyExistsException($entity);
        }

        File::ensureDirectoryExists(
            $this->getMigrationDirectoryPath($entity)
        );

        File::put(
            path: $this->getMigrationFilePath($entity),
            contents: $this->getMigrationContents($entity)
        );

        if ($markEntityAsMigrated) {
            $this->markEntityAsMigrated($entity);
        }

        return $this;
    }

    public function migrationExists(Entity $entity): bool
    {
        return File::exists(
            $this->getMigrationFilePath($entity)
        );
    }

    public function getMigrationDirectoryPath(Entity $entity): string
    {
        return Config::get('bonsaicms-metamodel-database.generate.migration.folder').'/';
    }

    public function getMigrationFilePath(Entity $entity): string
    {
        return
            $this->getMigrationDirectoryPath($entity).
            $this->getMigrationFileName($entity);
    }

    public function getMigrationName(Entity $entity): string
    {
        return $this->getMigrationFileNamePrefix($entity).
            '_create_'.
            $entity->table.
            '_table';
    }

    public function getMigrationFileName(Entity $entity): string
    {
        return $this->getMigrationName($entity).
            Config::get('bonsaicms-metamodel-database.generate.migration.fileSuffix');
    }

    protected function getMigrationFileNamePrefix(Entity $entity): string
    {
        return $entity->created_at->format('Y_m_d_His');
    }

    public function getMigrationContents(Entity $entity): string
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
                return Stub::make('migration/column', [
                    'type' => $attribute->data_type,
                    'column' => $attribute->column,
                    'arguments' => '',
                    'decorators' => '',
                ]);
            })
            ->join(PHP_EOL);
    }

    public function wasEntityMigrated(Entity $entity): bool
    {
        return collect($this->migrationRepository->getRan())
            ->contains(
                $this->getMigrationName($entity)
            );
    }

    public function markEntityAsMigrated(Entity $entity): self
    {
        $this->migrationRepository->log(
            $this->getMigrationName($entity),
            $this->migrationRepository->getNextBatchNumber()
        );

        return $this;
    }

    public function markEntityAsNotMigrated(Entity $entity): self
    {
        $this->migrationRepository->delete((object)[
            'migration' => $this->getMigrationName($entity)
        ]);

        return $this;
    }
}
