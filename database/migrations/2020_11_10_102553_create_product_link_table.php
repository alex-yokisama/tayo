<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductLinkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_link', function (Blueprint $table) {
            $table->id()->autoincrement();
            $table->foreignId('product_id')->nullable();
            $table->foreignId('currency_id');
            $table->foreignId('agent_id');
            $table->boolean('is_primary')->default(false);
            $table->double('price_old', 8, 2);
            $table->double('price_new', 8, 2);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('product');
            $table->foreign('currency_id')->references('id')->on('currency');
            $table->foreign('agent_id')->references('id')->on('agent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_link');
    }
}
