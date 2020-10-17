<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->uuid('id')->primary();
            $table->integer('number')->unique();
            $table->boolean('active')->default(false);
            $table->boolean('can_enlist')->default(false);
            $table->boolean('processing')->default(false);
            $table->unsignedTinyInteger('turn_duration');
            $table->unsignedSmallInteger('max_players')->default(0);
            $table->unsignedTinyInteger('dimensions');
            $table->longText('map')->nullable();
            $table->datetime('start_date');
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
        Schema::dropIfExists('games');
    }
}
