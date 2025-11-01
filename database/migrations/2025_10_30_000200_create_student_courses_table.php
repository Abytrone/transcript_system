<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('academic_year');
            $table->integer('semester');
            $table->string('grade')->nullable();
            $table->decimal('gpa', 3, 2)->nullable();
            $table->integer('credit_hours');
            $table->enum('status', ['enrolled', 'completed', 'failed', 'resit'])->default('enrolled');
            $table->boolean('is_resit')->default(false);
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'academic_year', 'semester']);
            $table->index(['course_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_courses');
    }
};

