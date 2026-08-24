<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_journal_sections', function (Blueprint $table): void {
            $table->id();
            $table->string('eyebrow');
            $table->string('title');
            $table->text('description');
            $table->string('explore_label');
            $table->string('explore_href');
            $table->string('read_label');
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_journal_sections');
    }
};
