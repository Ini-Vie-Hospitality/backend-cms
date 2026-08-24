<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_navbar_links', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('navbar_id')->constrained('homepage_navbars')->cascadeOnDelete();
            $table->string('audience');
            $table->string('label');
            $table->string('href');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['navbar_id', 'audience', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_navbar_links');
    }
};
