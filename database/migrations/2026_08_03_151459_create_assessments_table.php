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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_id')
                ->constrained('courses')
                ->cascadeOnDelete();

            $table->foreignId('lesson_id')
                ->nullable()
                ->constrained('lessons')
                ->onDelete('set null');

            $table->string('title');
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->string('type');
            $table->boolean('hidden')->default(false);
            $table->unsignedSmallInteger('duration_minutes')->default(0);
            $table->unsignedInteger('total_points')->default(0);
            $table->unsignedInteger('attempts')->default(false);
            $table->unsignedInteger('passing_score')->default(0);
            $table->boolean('shuffle_questions')->default(false);
            $table->boolean('shuffle_answers')->default(false);
            $table->boolean('show_result')->default(false);
            $table->boolean('show_correct_answers')->default(false);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();

            $table->index([
                'title',
                'slug',
            ]);
            $table->fullText([
                'title',
                'description',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
