<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_gen_red_cats_suf_gen', function (Blueprint $table) {
            $table->id();
            $table->string('some_nullable_string_attribute')->nullable();
            $table->string('some_required_string_attribute');
            $table->string('some_required_string_attribute_with_default')->default('some default string');
            $table->text('some_nullable_text_attribute')->nullable();
            $table->text('some_required_text_attribute');
            $table->text('some_required_text_attribute_with_default')->default('some default text');
            $table->boolean('some_nullable_boolean_attribute')->nullable();
            $table->boolean('some_required_boolean_attribute');
            $table->boolean('some_required_boolean_attribute_with_default')->default(true);
            $table->integer('some_nullable_integer_attribute')->nullable();
            $table->integer('some_required_integer_attribute');
            $table->integer('some_required_integer_attribute_with_default')->default(123);
            $table->date('some_nullable_date_attribute')->nullable();
            $table->date('some_required_date_attribute');
            $table->date('some_required_date_attribute_with_default')->default('2022-02-01');
            $table->time('some_nullable_time_attribute')->nullable();
            $table->time('some_required_time_attribute');
            $table->time('some_required_time_attribute_with_default')->default('12:34:56');
            $table->datetime('some_nullable_datetime_attribute')->nullable();
            $table->datetime('some_required_datetime_attribute');
            $table->datetime('some_required_datetime_attribute_with_default')->default('2022-02-01 12:34:56');
            $table->json('some_nullable_json_attribute')->nullable();
            $table->json('some_required_json_attribute');
            $table->json('some_required_json_attribute_with_default')->default('{"integer":123,"bool":true,"array":[1,2,3]}');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pre_gen_red_cats_suf_gen');
    }
};
