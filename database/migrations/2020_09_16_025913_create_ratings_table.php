<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->integer('rated_user_id')->unsigned();
            $table->integer('rating_user_id')->unsigned();
            $table->integer('rating')->unsigned();
            $table->string('rating_comment','255');
            $table->timestamps();

            $table->foreign('rated_user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('rating_user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->unique(['rated_user_id','rating_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ratingss');
    }
}
