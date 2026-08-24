<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_culinary_destinations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('section_id')->constrained('homepage_culinary_sections')->cascadeOnDelete();
            $table->string('name');
            $table->string('location');
            $table->string('eyebrow');
            $table->text('description');
            $table->string('schedule');
            $table->string('cta_label');
            $table->string('image_path');
            $table->string('image_alt');
            $table->string('href');
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_culinary_destinations');
    }
};
