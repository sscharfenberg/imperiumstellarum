<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageRecipientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_recipients', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->uuid('id')->primary();
            $table->uuid('game_id');
            $table->uuid('message_id');
            $table->uuid('recipient_id');
            $table->boolean('read')->default(false);
            $table->boolean('deleted')->default(false);
            $table->foreign('game_id')->references('id')->on('games')
                ->onDelete('cascade');
            $table->foreign('message_id')->references('id')->on('messages')
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
        Schema::dropIfExists('message_recipients');
    }
}
