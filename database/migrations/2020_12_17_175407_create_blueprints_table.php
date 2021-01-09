<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlueprintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blueprints', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->uuid('id')->primary();
            $table->uuid('game_id');
            $table->uuid('player_id');
            $table->enum('hull_type', array_keys(config('rules.ships.hullTypes')));
            $table->string('modules', 100);
            $table->string('name', config('rules.blueprints.className.max'));
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
        Schema::dropIfExists('blueprints');
    }
}
