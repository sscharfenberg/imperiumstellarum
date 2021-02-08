<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $length = (38 * 10) + 2 + config('rules.messages.recipients.max') - 1;
        Schema::create('messages', function (Blueprint $table) use ($length) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->uuid('id')->primary();
            $table->uuid('game_id');
            $table->uuid('player_id');
            $table->uuid('sender_id')->nullable();
            $table->uuid('message_id')->nullable(); // replys use this as foreign key to message
            $table->string('body', config('rules.messages.text.max'));
            $table->string('subject', config('rules.messages.subject.max'));
            $table->boolean('read')->default(false);
            $table->string('recipient_ids', $length);
            $table->foreign('game_id')->references('id')->on('games')
                ->onDelete('cascade');
            $table->foreign('player_id')->references('id')->on('players')
                ->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('players')
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
        Schema::dropIfExists('messages');
    }
}
