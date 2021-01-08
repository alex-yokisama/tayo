<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmReviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('film_review', function (Blueprint $table) {
            $table->id()->autoincrement();
            $table->foreignId('film_id');
            $table->string('title');
            $table->string('subtitle');
            $table->text('summary')->nullable();
            $table->text('positive')->nullable();
            $table->text('negative')->nullable();
            $table->unsignedSmallInteger('rating')->default(0);
            $table->timestamps();

            $table->foreign('film_id')->references('id')->on('film');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('film_review');
    }
}
