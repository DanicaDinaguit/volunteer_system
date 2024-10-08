<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('receiver_id');
            $table->unsignedBigInteger('thread_id')->nullable();
            $table->text('message_content');
            $table->string('sender_type'); // 'admin' or 'volunteer'
            $table->string('receiver_type'); // 'admin' or 'volunteer'
            $table->timestamp('read_at')->nullable(); 
            $table->timestamps();

            $table->foreign('thread_id')->references('id')->on('message_threads')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
