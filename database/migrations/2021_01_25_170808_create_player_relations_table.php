<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayerRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_relations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->uuid('id')->primary();
            $table->uuid('game_id');
            $table->uuid('player_id');
            $table->uuid('recipient_id');
            $table->unsignedTinyInteger('status')->default(1);
            $table->foreign('game_id')->references('id')->on('games')
                ->onDelete('cascade');
            $table->foreign('player_id')->references('id')->on('players')
                ->onDelete('cascade');
            $table->foreign('recipient_id')->references('id')->on('players')
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
        Schema::dropIfExists('player_relations');
    }
}
