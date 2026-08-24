<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_footer_contacts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('footer_id')->constrained('homepage_footers')->cascadeOnDelete();
            $table->string('title');
            $table->string('phone_label')->nullable();
            $table->string('phone_href')->nullable();
            $table->string('email_label')->nullable();
            $table->string('email_href')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_footer_contacts');
    }
};
