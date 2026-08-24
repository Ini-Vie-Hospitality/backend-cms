<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_wellness_escape_category', function (Blueprint $table): void {
            $table->foreignId('wellness_escape_id')->constrained('homepage_wellness_escapes')->cascadeOnDelete();
            $table->foreignId('wellness_category_id')->constrained('homepage_wellness_categories')->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->primary(['wellness_escape_id', 'wellness_category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_wellness_escape_category');
    }
};
