<?php

use Illuminate\Support\Facades\Schema;

test('entities table exists in database', function () {
    expect(Schema::hasTable('bonsaicms_metamodel_entities'))->toBeTrue();
});

test('attributes table exists in database', function () {
    expect(Schema::hasTable('bonsaicms_metamodel_attributes'))->toBeTrue();
});

test('relationships table exists in database', function () {
    expect(Schema::hasTable('bonsaicms_metamodel_relationships'))->toBeTrue();
});
