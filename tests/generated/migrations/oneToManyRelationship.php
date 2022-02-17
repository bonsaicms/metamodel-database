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
        Schema::table('pre_gen_red_cats_table_name_suf_gen', function (Blueprint $table) {
            $table->foreignId('blue_dog_foreign_key_id')
                ->constrained('pre_gen_blue_dogs_table_name_suf_gen')
                ->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pre_gen_red_cats_table_name_suf_gen', function (Blueprint $table) {
            $table->dropColumn('blue_dog_foreign_key_id');
        });
    }
};
