<?php

use Illuminate\Support\Facades\Schema;

test('entities table exists in database', function () {
    expect(Schema::hasTable('pre_met_entities_suf_met'))->toBeTrue();
});

test('attributes table exists in database', function () {
    expect(Schema::hasTable('pre_met_attributes_suf_met'))->toBeTrue();
});

test('relationships table exists in database', function () {
    expect(Schema::hasTable('pre_met_relationships_suf_met'))->toBeTrue();
});
