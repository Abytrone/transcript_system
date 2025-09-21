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
        // Ensure programs table exists before creating courses (in case of ordering)
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

        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('credits');
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->integer('level'); // 100, 200, 300, 400, etc.
            $table->integer('semester'); // 1, 2
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
