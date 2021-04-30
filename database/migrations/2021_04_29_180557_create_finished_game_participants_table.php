<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinishedGameParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finished_game_participants', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->uuid('id')->primary();
            $table->uuid('game_id');
            $table->string('name', config('rules.player.name.max'));
            $table->string('ticker', config('rules.player.ticker.max'));
            $table->string('colour', 6);
            $table->boolean('died')->default(false);
            $table->float('population', 10,6);
            $table->unsignedTinyInteger('stars')->default(0);
            $table->longText('ships')->nullable();
            $table->longText('shipyards')->nullable();
            $table->foreign('game_id')->references('id')->on('finished_games')
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
        Schema::dropIfExists('finished_game_participants');
    }
}
