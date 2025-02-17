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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('folder_id')->default(0);
            $table->unsignedBigInteger('teacher_id');
            $table->string('teacher_name');
            $table->string('class_name');
            $table->string('subject');
            $table->string('section');
            $table->string('room_code')->unique();
            $table->timestamps();

            // $table->foreign('folder_id')->references('id')->on('folders')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
