<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_journal_story_title_lines', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('story_id')->constrained('homepage_journal_stories')->cascadeOnDelete();
            $table->unsignedInteger('line_number');
            $table->string('text');
            $table->unique(['story_id', 'line_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_journal_story_title_lines');
    }
};
