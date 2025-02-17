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
        Schema::create('student_rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('folder_id')->default(0);
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('student_id');
            $table->string('student_name');
            $table->string('teacher_name');
            $table->string('class_name');
            $table->string('subject');
            $table->string('section');
            $table->timestamps();

            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_rooms');
    }
};
