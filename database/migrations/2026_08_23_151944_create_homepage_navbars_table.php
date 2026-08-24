<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_navbars', function (Blueprint $table): void {
            $table->id();
            $table->string('logo_path');
            $table->string('logo_alt');
            $table->string('logo_href');
            $table->string('book_label');
            $table->string('book_href');
            $table->string('mobile_eyebrow');
            $table->string('mobile_open_label');
            $table->string('mobile_close_label');
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_navbars');
    }
};
