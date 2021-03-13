<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_reports', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->uuid('id')->primary();
            $table->uuid('game_id');
            $table->uuid('message_id');
            $table->uuid('reporter_id');
            $table->uuid('reportee_id');
            $table->string('comment', config('rules.reports.comment.max'));
            $table->unsignedBigInteger('resolved_admin')->nullable();
            $table->unsignedBigInteger('suspension_duration')->nullable();
            $table->string('admin_reportee_msg', config('rules.reports.reportMessage.max'))->nullable();
            $table->string('admin_reporter_msg', config('rules.reports.reportMessage.max'))->nullable();
            $table->foreign('game_id')->references('id')->on('games')
                ->onDelete('cascade');
            $table->foreign('message_id')->references('id')->on('messages')
                ->onDelete('cascade');
            $table->foreign('reporter_id')->references('id')->on('players')
                ->onDelete('cascade');
            $table->foreign('reportee_id')->references('id')->on('players')
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
        Schema::dropIfExists('message_reports');
    }
}
