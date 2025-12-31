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
        Schema::create('scores', function (Blueprint $table) {
    $table->id();

    $table->foreignId('student_id')->constrained()->cascadeOnDelete();
    $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
    $table->foreignId('class_room_id')->constrained()->cascadeOnDelete();

    $table->enum('semester', ['ganjil', 'genap']);
    $table->integer('daily_score')->nullable();
    $table->integer('uts_score')->nullable();
    $table->integer('uas_score')->nullable();

    $table->timestamps();

    $table->unique([
        'student_id',
        'subject_id',
        'class_room_id',
        'semester'
    ]);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
