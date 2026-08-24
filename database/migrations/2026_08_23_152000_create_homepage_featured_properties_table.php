<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_featured_properties', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('section_id')->constrained('homepage_featured_property_sections')->cascadeOnDelete();
            $table->string('name');
            $table->string('category');
            $table->text('description');
            $table->string('image_path');
            $table->string('image_alt');
            $table->string('href');
            $table->string('cta_label');
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->index(['section_id', 'status', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_featured_properties');
    }
};
