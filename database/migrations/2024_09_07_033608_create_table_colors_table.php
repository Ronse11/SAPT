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
        Schema::create('table_colors', function (Blueprint $table) {
            $table->id();
            $table->string('table_id');
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('room_id');
            $table->integer('row');
            $table->string('column');
            $table->string('color_name');
            $table->string('type');
            $table->timestamps();

            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_colors');
    }
};
