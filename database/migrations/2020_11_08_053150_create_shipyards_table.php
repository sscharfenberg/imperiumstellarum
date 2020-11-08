<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipyardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipyards', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->uuid('id')->primary();
            $table->uuid('planet_id');
            $table->uuid('game_id');
            $table->uuid('player_id');
            $table->boolean('small')->default(false);
            $table->boolean('medium')->default(false);
            $table->boolean('large')->default(false);
            $table->boolean('xlarge')->default(false);
            $table->unsignedSmallInteger('until_complete');
            $table->foreign('planet_id')->references('id')->on('planets')
                ->onDelete('cascade');
            $table->foreign('game_id')->references('id')->on('games')
                ->onDelete('cascade');
            $table->foreign('player_id')->references('id')->on('players')
                ->onDelete('cascade');
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipyards');
    }
}
