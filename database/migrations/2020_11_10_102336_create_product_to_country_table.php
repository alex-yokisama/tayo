<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductToCountryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_to_country', function (Blueprint $table) {
            $table->id()->autoincrement();
            $table->foreignId('product_id');
            $table->foreignId('country_id');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('product');
            $table->foreign('country_id')->references('id')->on('country');
            $table->unique(['product_id', 'country_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_to_country');
    }
}
