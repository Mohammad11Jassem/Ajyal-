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
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_id')->constrained()->onDelete('cascade');
            // $table->unsignedBigInteger('author_id'); // student or teacher
            // $table->string('author_type');
            $table->morphs('author');
            $table->text('body');
            $table->boolean('is_fqa')->default(false);
            $table->timestamps();

            // $table->index(['author_id', 'author_type']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};
