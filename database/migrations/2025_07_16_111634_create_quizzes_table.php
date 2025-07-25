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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('curriculum_id')->constrained()->onDelete('cascade');
            $table->foreignId('curriculum_teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('topic_id')->nullable()->constrained()->onDelete('cascade');
            // $table->foreignId('topic_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['Timed', 'worksheet']);
            $table->boolean('available')->default(false);
            $table->dateTime('start_time')->nullable();
            // $table->date('end_time');
            $table->decimal('duration')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
