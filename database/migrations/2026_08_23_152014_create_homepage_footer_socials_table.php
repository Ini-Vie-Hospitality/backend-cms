<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_footer_socials', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('footer_id')->constrained('homepage_footers')->cascadeOnDelete();
            $table->string('label');
            $table->string('href');
            $table->string('icon');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_footer_socials');
    }
};
