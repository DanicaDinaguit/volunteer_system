<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('message_threads', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // Name of the thread (optional)
            $table->boolean('is_group_chat')->default(false);
            $table->timestamps();
        });

        Schema::create('message_thread_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thread_id')->constrained('message_threads')->onDelete('cascade');
            $table->unsignedBigInteger('participant_id');
            $table->string('participant_type'); // 'admin' or 'volunteer'
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('message_thread_participants');
        Schema::dropIfExists('message_threads');
    }
};
