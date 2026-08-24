<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_publish_versions', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('version');
            $table->json('payload');
            $table->json('media_paths')->nullable();
            $table->string('action')->default('import');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique('version');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_publish_versions');
    }
};
