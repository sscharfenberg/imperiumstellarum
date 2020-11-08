<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStorageUpgradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storage_upgrades', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->uuid('id')->primary();
            $table->uuid('player_id');
            $table->uuid('game_id');
            $table->enum('resource_type', array_keys(config('rules.player.resourceTypes')));
            $table->unsignedTinyInteger('new_level');
            $table->unsignedTinyInteger('until_complete');
            $table->foreign('player_id')->references('id')->on('players')
                ->onDelete('cascade');
            $table->foreign('game_id')->references('id')->on('games')
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
        Schema::dropIfExists('storage_upgrades');
    }
}
