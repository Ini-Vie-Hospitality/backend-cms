<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_journal_stories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('section_id')->constrained('homepage_journal_sections')->cascadeOnDelete();
            $table->string('external_key');
            $table->string('category');
            $table->text('description');
            $table->string('reading_time');
            $table->string('image_path');
            $table->string('image_alt');
            $table->string('href');
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->unique(['section_id', 'external_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_journal_stories');
    }
};
