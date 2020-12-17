<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimilarProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('similar_products', function (Blueprint $table) {
            $table->foreignId('product_id');
            $table->foreignId('similar_id');

            $table->foreign('product_id')->references('id')->on('product')->onDelete('cascade');
            $table->foreign('similar_id')->references('id')->on('product')->onDelete('cascade');
            $table->unique(['product_id', 'similar_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('simiar_product');
    }
}
