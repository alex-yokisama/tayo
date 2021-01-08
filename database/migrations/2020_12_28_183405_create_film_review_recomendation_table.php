<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmReviewRecomendationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('film_review_recomendation', function (Blueprint $table) {
            $table->foreignId('film_review_id');
            $table->foreignId('film_id');

            $table->foreign('film_review_id')->references('id')->on('film_review')->onDelete('cascade');
            $table->foreign('film_id')->references('id')->on('film')->onDelete('cascade');
            $table->unique(['film_review_id', 'film_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('film_review_recomendation');
    }
}
