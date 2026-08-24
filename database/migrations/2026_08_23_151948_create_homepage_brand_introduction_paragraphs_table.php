<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_brand_introduction_paragraphs', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('brand_introduction_id');
            $table->foreign('brand_introduction_id', 'hbi_paragraphs_parent_fk')->references('id')->on('homepage_brand_introductions')->cascadeOnDelete();
            $table->unsignedTinyInteger('slot');
            $table->text('body');
            $table->unique(['brand_introduction_id', 'slot'], 'hbi_paragraphs_slot_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_brand_introduction_paragraphs');
    }
};
