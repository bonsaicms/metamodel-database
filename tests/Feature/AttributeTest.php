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

    $this->assertDatabaseHas('pre_met_attributes_suf_met', [
        'column' => 'original',
    ]);

    expect(Schema::hasColumn('pre_gen_table_suf_gen', 'original'))->toBeTrue();
});

it('drops the column when the attribute is deleted', function () {
    Attribute::factory()
        ->for($this->entity)
        ->create([
            'nullable' => true,
            'column' => 'original',
        ])
        ->delete();

    $this->assertDatabaseMissing('pre_met_attributes_suf_met', [
        'column' => 'original',
    ]);

    expect(Schema::hasColumn('pre_gen_table_suf_gen', 'original'))->toBeFalse();
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

    $this->assertDatabaseMissing('pre_met_attributes_suf_met', [
        'column' => 'original',
    ]);
    $this->assertDatabaseHas('pre_met_attributes_suf_met', [
        'column' => 'new',
    ]);

    expect(Schema::hasColumn('pre_gen_table_suf_gen', 'original'))->toBeFalse();
    expect(Schema::hasColumn('pre_gen_table_suf_gen', 'new'))->toBeTrue();
});

it('keeps the column name when the attribute was not saved', function () {
    $attribute = Attribute::factory()
        ->for($this->entity)
        ->create([
            'nullable' => true,
            'column' => 'original',
        ]);

    $attribute->column = 'new';

    $this->assertDatabaseHas('pre_met_attributes_suf_met', [
        'column' => 'original',
    ]);
    $this->assertDatabaseMissing('pre_met_attributes_suf_met', [
        'column' => 'new',
    ]);

    expect(Schema::hasColumn('pre_gen_table_suf_gen', 'original'))->toBeTrue();
    expect(Schema::hasColumn('pre_gen_table_suf_gen', 'new'))->toBeFalse();
});

it('changes column data type when attribute is updated', function () {
    $attribute = Attribute::factory()
        ->for($this->entity)
        ->create([
            'nullable' => true,
            'column' => 'original',
            'data_type' => 'integer',
        ]);

    $this->assertDatabaseHas('pre_met_attributes_suf_met', [
        'data_type' => 'integer',
    ]);
    expect(DB::getSchemaBuilder()->getColumnType('pre_gen_table_suf_gen', 'original'))->toBe('integer');

    $attribute->data_type = 'string';
    $attribute->save();

    $this->assertDatabaseHas('pre_met_attributes_suf_met', [
        'data_type' => 'string',
    ]);
    expect(DB::getSchemaBuilder()->getColumnType('pre_gen_table_suf_gen', 'original'))->toBe('string');
});

it('keeps the column data type when attribute was not saved', function () {
    $attribute = Attribute::factory()
        ->for($this->entity)
        ->create([
            'nullable' => true,
            'column' => 'original',
            'data_type' => 'string',
        ]);

    $this->assertDatabaseHas('pre_met_attributes_suf_met', [
        'data_type' => 'string',
    ]);
    expect(DB::getSchemaBuilder()->getColumnType('pre_gen_table_suf_gen', 'original'))->toBe('string');

    $attribute->data_type = 'integer';

    $this->assertDatabaseHas('pre_met_attributes_suf_met', [
        'data_type' => 'string',
    ]);
    $this->assertDatabaseMissing('pre_met_attributes_suf_met', [
        'data_type' => 'integer',
    ]);
    expect(DB::getSchemaBuilder()->getColumnType('pre_gen_table_suf_gen', 'original'))->toBe('string');
});
