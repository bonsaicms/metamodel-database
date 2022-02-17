<?php

namespace BonsaiCms\MetamodelDatabase\Traits;

use Illuminate\Support\Str;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\File;
use BonsaiCms\MetamodelDatabase\Stub;
use Illuminate\Support\Facades\Config;
use BonsaiCms\Metamodel\Models\Relationship;
use BonsaiCms\Support\PhpDependenciesCollection;
use BonsaiCms\Support\Stubs\Actions\TrimNewLinesFromTheEnd;
use BonsaiCms\MetamodelDatabase\Exceptions\RelationshipMigrationAlreadyExistsException;

trait WorksWithRelationshipMigrations
{
    public function deleteRelationshipMigration(Relationship $relationship, bool $markRelationshipAsNotMigrated = true): self
    {
        if ($this->relationshipMigrationExists($relationship)) {
            File::delete(
                $this->getRelationshipMigrationFilePath($relationship)
            );

            if ($markRelationshipAsNotMigrated) {
                $this->markRelationshipAsNotMigrated($relationship);
            }
        }

        return $this;
    }

    public function regenerateRelationshipMigration(Relationship $relationship): self
    {
        if ($relationship->exists) {
            $markRelationship = !$this->wasRelationshipMigrated($relationship);
            $this->deleteRelationshipMigration($relationship, $markRelationship);
            $this->generateRelationshipMigration($relationship, $markRelationship);
        } else {
            $this->deleteRelationshipMigration($relationship);
        }

        return $this;
    }

    /**
     * @throws RelationshipMigrationAlreadyExistsException
     */
    public function generateRelationshipMigration(Relationship $relationship, bool $markRelationshipAsMigrated = true): self
    {
        if ($this->relationshipMigrationExists($relationship)) {
            throw new RelationshipMigrationAlreadyExistsException($relationship);
        }

        File::ensureDirectoryExists(
            $this->getRelationshipMigrationDirectoryPath($relationship)
        );

        File::put(
            path: $this->getRelationshipMigrationFilePath($relationship),
            contents: $this->getRelationshipMigrationContents($relationship)
        );

        if ($markRelationshipAsMigrated) {
            $this->markRelationshipAsMigrated($relationship);
        }

        return $this;
    }

    public function relationshipMigrationExists(Relationship $relationship): bool
    {
        return File::exists(
            $this->getRelationshipMigrationFilePath($relationship)
        );
    }

    public function getRelationshipMigrationDirectoryPath(Relationship $relationship): string
    {
        return Config::get('bonsaicms-metamodel-database.generate.migration.relationship.folder').'/';
    }

    public function getRelationshipMigrationFilePath(Relationship $relationship): string
    {
        return
            $this->getRelationshipMigrationDirectoryPath($relationship).
            $this->getRelationshipMigrationFileName($relationship);
    }

    public function getRelationshipMigrationName(Relationship $relationship): string
    {
        return $this->getRelationshipMigrationFileNamePrefix($relationship).
            '_add_'.
            $relationship->leftEntity->table.
            '_'.
            $relationship->rightEntity->table.
            '_'.
            Str::snake($relationship->cardinality).
            '_relationship';
    }

    public function getRelationshipMigrationFileName(Relationship $relationship): string
    {
        return $this->getRelationshipMigrationName($relationship).
            Config::get('bonsaicms-metamodel-database.generate.migration.relationship.fileSuffix');
    }

    protected function getRelationshipMigrationFileNamePrefix(Relationship $relationship): string
    {
        return $relationship->created_at->format('Y_m_d_His');
    }

    public function getRelationshipMigrationContents(Relationship $relationship): string
    {
        return Stub::make('migrations/relationship/file', [
            'dependencies' => $this->resolveRelationshipMigrationDependencies($relationship),
            'methods' => $this->resolveRelationshipMigrationMethods($relationship),
        ]);
    }

    protected function resolveRelationshipMigrationDependencies(Relationship $relationship): string
    {
        $dependencies = new PhpDependenciesCollection(
            Config::get('bonsaicms-metamodel-database.generate.migration.relationship.dependencies')
        );

        return $dependencies->toPhpUsesString('');
    }

    protected function resolveRelationshipMigrationMethods(Relationship $relationship): string
    {
        return app(Pipeline::class)
            ->send(
                collect([
                    $this->resolveRelationshipMigrationMethodUp($relationship),
                    $this->resolveRelationshipMigrationMethodDown($relationship),
                ])
                    ->join(PHP_EOL)
            )
            ->through([
                TrimNewLinesFromTheEnd::class,
            ])
            ->thenReturn();
    }

    protected function resolveRelationshipMigrationMethodUp(Relationship $relationship): string
    {
        $method = "resolve{$relationship->cardinality}RelationshipMigrationMethodUp";

        return $this->$method($relationship);
    }

    protected function resolveRelationshipMigrationMethodDown(Relationship $relationship): string
    {
        $method = "resolve{$relationship->cardinality}RelationshipMigrationMethodDown";

        return $this->$method($relationship);
    }

    public function wasRelationshipMigrated(Relationship $relationship): bool
    {
        return collect($this->migrationRepository->getRan())
            ->contains(
                $this->getRelationshipMigrationName($relationship)
            );
    }

    public function markRelationshipAsMigrated(Relationship $relationship): self
    {
        $this->migrationRepository->log(
            $this->getRelationshipMigrationName($relationship),
            $this->migrationRepository->getNextBatchNumber()
        );

        return $this;
    }

    public function markRelationshipAsNotMigrated(Relationship $relationship): self
    {
        $this->migrationRepository->delete((object)[
            'migration' => $this->getRelationshipMigrationName($relationship)
        ]);

        return $this;
    }

    /*
     * OneToOne
     */

    protected function resolveOneToOneRelationshipMigrationMethodUp(Relationship $relationship): string
    {
        return Stub::make('migrations/relationship/oneToOneMethodUp', [
            'thisTable' => $relationship->rightEntity->realTableName,
            'relatedTable' => $relationship->leftEntity->realTableName,
            'foreignKeyColumn' => $relationship->right_foreign_key,
        ]);
    }

    protected function resolveOneToOneRelationshipMigrationMethodDown(Relationship $relationship): string
    {
        return Stub::make('migrations/relationship/oneToOneMethodDown', [
            'thisTable' => $relationship->rightEntity->realTableName,
            'foreignKeyColumn' => $relationship->right_foreign_key,
        ]);
    }

    /*
     * OneToMany
     */

    protected function resolveOneToManyRelationshipMigrationMethodUp(Relationship $relationship): string
    {
        return Stub::make('migrations/relationship/oneToManyMethodUp', [
            'thisTable' => $relationship->rightEntity->realTableName,
            'relatedTable' => $relationship->leftEntity->realTableName,
            'foreignKeyColumn' => $relationship->right_foreign_key,
        ]);
    }

    protected function resolveOneToManyRelationshipMigrationMethodDown(Relationship $relationship): string
    {
        return Stub::make('migrations/relationship/oneToManyMethodDown', [
            'thisTable' => $relationship->rightEntity->realTableName,
            'foreignKeyColumn' => $relationship->right_foreign_key,
        ]);
    }

    /*
     * ManyToMany
     */

    protected function resolveManyToManyRelationshipMigrationMethodUp(Relationship $relationship): string
    {
        return Stub::make('migrations/relationship/manyToManyMethodUp', [
            'pivotTable' => $relationship->realPivotTableName,
            'leftForeignKey' => $relationship->left_foreign_key,
            'rightForeignKey' => $relationship->right_foreign_key,
            'leftTable' => $relationship->leftEntity->realTableName,
            'rightTable' => $relationship->rightEntity->realTableName,
        ]);
    }

    protected function resolveManyToManyRelationshipMigrationMethodDown(Relationship $relationship): string
    {
        return Stub::make('migrations/relationship/manyToManyMethodDown', [
            'pivotTable' => $relationship->realPivotTableName,
        ]);
    }
}
