<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ships', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->uuid('id')->primary();
            $table->uuid('game_id');
            $table->uuid('player_id');
            $table->uuid('fleet_id')->nullable(); // tmp
            $table->uuid('shipyard_id')->nullable();
            $table->enum('hull_type', array_keys(config('rules.ships.hullTypes')));
            $table->string('name', config('rules.ships.name.max'));
            $table->unsignedSmallInteger('dmg_plasma')->default(0);
            $table->unsignedSmallInteger('dmg_railgun')->default(0);
            $table->unsignedSmallInteger('dmg_missile')->default(0);
            $table->unsignedSmallInteger('dmg_laser')->default(0);
            $table->unsignedSmallInteger('hp_armour_max')->default(0);
            $table->unsignedSmallInteger('hp_armour_current')->default(0);
            $table->unsignedSmallInteger('hp_shields_max')->default(0);
            $table->unsignedSmallInteger('hp_shields_current')->default(0);
            $table->unsignedSmallInteger('hp_structure_max')->default(0);
            $table->unsignedSmallInteger('hp_structure_current')->default(0);
            $table->boolean('ftl')->default(false);
            $table->boolean('colony')->default(false);
            $table->unsignedTinyInteger('acceleration')->default(1);
            $table->foreign('game_id')->references('id')->on('games')
                ->onDelete('cascade');
            $table->foreign('player_id')->references('id')->on('players')
                ->onDelete('cascade');
            $table->foreign('shipyard_id')->references('id')->on('shipyards')
                ->onDelete('cascade');
            // TODO: foreign for fleet_id
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ships');
    }
}
