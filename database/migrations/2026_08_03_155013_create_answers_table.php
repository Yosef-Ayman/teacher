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
        Schema::create('answers', function (Blueprint $table) {
            $table->id();

            $table->foreignUuid('submission_id')
                ->constrained('submissions')
                ->cascadeOnDelete();

            $table->foreignId('question_id')
                ->constrained('questions')
                ->cascadeOnDelete();

            $table->string('question_type');

            $table->foreignId('option_id')
                ->nullable()
                ->constrained('options')
                ->onDelete('set null');

            $table->text('answer_text')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->unsignedSmallInteger('earned_points')->nullable();
            $table->unsignedSmallInteger('question_points');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
