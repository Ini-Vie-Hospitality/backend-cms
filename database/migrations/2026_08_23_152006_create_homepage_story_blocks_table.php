<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_story_blocks', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('section_id')->constrained('homepage_story_sections')->cascadeOnDelete();
            $table->unsignedTinyInteger('slot');
            $table->string('title');
            $table->text('description');
            $table->string('image_path');
            $table->string('image_alt');
            $table->string('cta_label');
            $table->string('href');
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->unique(['section_id', 'slot']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_story_blocks');
    }
};
