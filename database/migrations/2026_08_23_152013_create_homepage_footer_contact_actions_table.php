<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_footer_contact_actions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('contact_id')->constrained('homepage_footer_contacts')->cascadeOnDelete();
            $table->string('label');
            $table->string('href');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_footer_contact_actions');
    }
};
