<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPriceChangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_price_change', function (Blueprint $table) {
            $table->id()->autoincrement();
            $table->foreignId('product_id');
            $table->double('price_old', 8, 2);
            $table->double('price_new', 8, 2);
            $table->string('reason')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('product');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_price_change');
    }
}
