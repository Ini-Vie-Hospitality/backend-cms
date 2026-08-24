<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_special_offer_sections', function (Blueprint $table): void {
            $table->id();
            $table->string('eyebrow');
            $table->string('title');
            $table->text('description');
            $table->string('all_offers_label');
            $table->string('all_offers_href');
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_special_offer_sections');
    }
};
