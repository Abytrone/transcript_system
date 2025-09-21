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
        // Ensure programs table exists before creating students
        if (!Schema::hasTable('programs')) {
            Schema::create('programs', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('name');
                $table->text('description')->nullable();
                $table->foreignId('department_id')->constrained()->onDelete('cascade');
                $table->unsignedTinyInteger('duration_years')->default(2);
                $table->unsignedSmallInteger('total_credits')->nullable();
                $table->enum('level', ['certificate', 'diploma', 'degree'])->default('diploma');
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->timestamps();
            });
        }

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('nationality')->default('Ghanaian');
            $table->text('address')->nullable();
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->integer('year_of_admission');
            $table->integer('year_of_completion')->nullable();
            $table->enum('status', ['active', 'graduated', 'dropped'])->default('active');
            $table->string('photo_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
