<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_brand_introduction_images', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('brand_introduction_id');
            $table->foreign('brand_introduction_id', 'hbi_images_parent_fk')->references('id')->on('homepage_brand_introductions')->cascadeOnDelete();
            $table->unsignedTinyInteger('slot');
            $table->string('image_path');
            $table->string('image_alt');
            $table->unique(['brand_introduction_id', 'slot'], 'hbi_images_slot_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_brand_introduction_images');
    }
};
