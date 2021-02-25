<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConstructionContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('construction_contracts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->uuid('id')->primary();
            $table->uuid('game_id');
            $table->uuid('player_id');
            $table->uuid('blueprint_id');
            $table->uuid('shipyard_id');
            $table->enum('hull_type', array_keys(config('rules.ships.hullTypes')));
            $table->unsignedTinyInteger('amount');
            $table->unsignedTinyInteger('amount_left');
            $table->unsignedTinyInteger('turns_per_ship');
            $table->unsignedTinyInteger('turns_left');
            $table->unsignedSmallInteger('costs_minerals');
            $table->unsignedSmallInteger('costs_energy');
            $table->float('costs_population', 8,6)->default(0);
            $table->unsignedSmallInteger('paid_minerals')->default(0);
            $table->unsignedSmallInteger('paid_energy')->default(0);
            $table->float('paid_population')->default(0);
            $table->boolean('notified')->default(false);
            $table->string('cached_ship', 1000); // json column
            $table->foreign('game_id')->references('id')->on('games')
                ->onDelete('cascade');
            $table->foreign('player_id')->references('id')->on('players')
                ->onDelete('cascade');
            $table->foreign('blueprint_id')->references('id')->on('blueprints')
                ->onDelete('cascade');
            $table->foreign('shipyard_id')->references('id')->on('shipyards')
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
        Schema::dropIfExists('construction_contracts');
    }
}
