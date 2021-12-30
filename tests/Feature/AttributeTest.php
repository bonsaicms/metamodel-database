<?php

use Illuminate\Support\Facades\Schema;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\Metamodel\Models\Attribute;

beforeEach(function () {
    $this->entity = Entity::factory()
        ->create([
            'table' => 'table',
        ]);
});

it('creates a column when an attribute is created', function () {
    Attribute::factory()
        ->for($this->entity)
        ->create([
            'nullable' => true,
            'column' => 'original',
        ]);

    $this->assertDatabaseHas('bonsaicms_metamodel_attributes', [
        'column' => 'original',
    ]);

    expect(Schema::hasColumn('bonsaicms_table', 'original'))->toBeTrue();
});

it('drops the column when the attribute is deleted', function () {
    Attribute::factory()
        ->for($this->entity)
        ->create([
            'nullable' => true,
            'column' => 'original',
        ])
        ->delete();

    $this->assertDatabaseMissing('bonsaicms_metamodel_attributes', [
        'column' => 'original',
    ]);

    expect(Schema::hasColumn('bonsaicms_table', 'original'))->toBeFalse();
});

it('renames the column when the attribute is updated', function () {
    $attribute = Attribute::factory()
        ->for($this->entity)
        ->create([
            'nullable' => true,
            'column' => 'original',
        ]);

    $attribute->column = 'new';
    $attribute->save();

    $this->assertDatabaseMissing('bonsaicms_metamodel_attributes', [
        'column' => 'original',
    ]);
    $this->assertDatabaseHas('bonsaicms_metamodel_attributes', [
        'column' => 'new',
    ]);

    expect(Schema::hasColumn('bonsaicms_table', 'original'))->toBeFalse();
    expect(Schema::hasColumn('bonsaicms_table', 'new'))->toBeTrue();
});

it('keeps the column name when the attribute was not saved', function () {
    $attribute = Attribute::factory()
        ->for($this->entity)
        ->create([
            'nullable' => true,
            'column' => 'original',
        ]);

    $attribute->column = 'new';

    $this->assertDatabaseHas('bonsaicms_metamodel_attributes', [
        'column' => 'original',
    ]);
    $this->assertDatabaseMissing('bonsaicms_metamodel_attributes', [
        'column' => 'new',
    ]);

    expect(Schema::hasColumn('bonsaicms_table', 'original'))->toBeTrue();
    expect(Schema::hasColumn('bonsaicms_table', 'new'))->toBeFalse();
});

it('changes column data type when attribute is updated', function () {
    $attribute = Attribute::factory()
        ->for($this->entity)
        ->create([
            'nullable' => true,
            'column' => 'original',
            'type' => 'string',
        ]);

    $this->assertDatabaseHas('bonsaicms_metamodel_attributes', [
        'type' => 'string',
    ]);
    expect(DB::getSchemaBuilder()->getColumnType('bonsaicms_table', 'original'))->toBe('string');

    $attribute->type = 'integer';
    $attribute->save();

    $this->assertDatabaseHas('bonsaicms_metamodel_attributes', [
        'type' => 'integer',
    ]);
    expect(DB::getSchemaBuilder()->getColumnType('bonsaicms_table', 'original'))->toBe('integer');
});

it('keeps the column data type when attribute was not saved', function () {
    $attribute = Attribute::factory()
        ->for($this->entity)
        ->create([
            'nullable' => true,
            'column' => 'original',
            'type' => 'string',
        ]);

    $this->assertDatabaseHas('bonsaicms_metamodel_attributes', [
        'type' => 'string',
    ]);
    expect(DB::getSchemaBuilder()->getColumnType('bonsaicms_table', 'original'))->toBe('string');

    $attribute->type = 'integer';

    $this->assertDatabaseHas('bonsaicms_metamodel_attributes', [
        'type' => 'string',
    ]);
    $this->assertDatabaseMissing('bonsaicms_metamodel_attributes', [
        'type' => 'integer',
    ]);
    expect(DB::getSchemaBuilder()->getColumnType('bonsaicms_table', 'original'))->toBe('string');
});
