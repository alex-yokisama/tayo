<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributeToCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_to_category', function (Blueprint $table) {
            $table->foreignId('attribute_id');
            $table->foreignId('category_id');

            $table->foreign('attribute_id')->references('id')->on('attribute')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('category')->onDelete('cascade');
            $table->unique(['attribute_id', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attribute_to_category');
    }
}
