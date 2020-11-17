<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributeOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_option', function (Blueprint $table) {
            $table->id()->autoincrement();
            $table->string('name');
            $table->foreignId('attribute_id');
            $table->timestamps();

            $table->foreign('attribute_id')->references('id')->on('attribute');
            $table->unique(['name', 'attribute_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attribute_option');
    }
}
