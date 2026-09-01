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
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lesson_id')
                ->constrained('lessons')
                ->cascadeOnDelete();

            $table->foreignId('video_id')
                ->nullable()
                ->constrained('videos')
                ->cascadeOnDelete();

            $table->foreignId('markdown_id')
                ->nullable()
                ->constrained('markdowns')
                ->cascadeOnDelete();

            $table->foreignId('assessment_id')
                ->nullable()
                ->constrained('assessments')
                ->cascadeOnDelete();

            $table->tinyInteger('position')->default(0);

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};
