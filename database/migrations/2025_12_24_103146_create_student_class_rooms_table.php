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
       Schema::create('student_class_rooms', function (Blueprint $table) {
    $table->id();

    $table->foreignId('student_id')->constrained()->cascadeOnDelete();
    $table->foreignId('class_room_id')->constrained()->cascadeOnDelete();

    $table->timestamps();

    $table->unique(['student_id', 'class_room_id']);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_class_rooms');
    }
};
