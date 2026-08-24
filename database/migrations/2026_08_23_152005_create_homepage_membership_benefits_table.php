<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_membership_benefits', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('membership_id')->constrained('homepage_memberships')->cascadeOnDelete();
            $table->string('label');
            $table->string('icon');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_membership_benefits');
    }
};
