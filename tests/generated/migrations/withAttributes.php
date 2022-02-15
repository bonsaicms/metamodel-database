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
            $table->string('some_string_attribute');
            $table->text('some_text_attribute');
            $table->boolean('some_boolean_attribute');
            $table->integer('some_integer_attribute');
            $table->date('some_date_attribute');
            $table->time('some_time_attribute');
            $table->datetime('some_datetime_attribute');
            $table->json('some_json_attribute');
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
