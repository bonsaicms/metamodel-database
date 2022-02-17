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
        Schema::create('pre_gen_blue_dog_red_cat_pivot_table_name_suf_gen', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('blue_dog_foreign_key_id')
                ->constrained('pre_gen_blue_dogs_table_name_suf_gen')
                ->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreignId('red_cat_foreign_key_id')
                ->constrained('pre_gen_red_cats_table_name_suf_gen')
                ->cascadeOnUpdate()->cascadeOnDelete();

            $table->primary([
                'blue_dog_foreign_key_id',
                'red_cat_foreign_key_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pre_gen_blue_dog_red_cat_pivot_table_name_suf_gen');
    }
};
