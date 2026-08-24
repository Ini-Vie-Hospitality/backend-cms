<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('concierge_knowledge_items', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('category')->nullable()->index();
            $table->longText('content');
            $table->text('embedding')->nullable();
            $table->char('content_hash', 64)->nullable()->index();
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });

        if (DB::getDriverName() === 'mariadb') {
            DB::statement('ALTER TABLE concierge_knowledge_items MODIFY embedding VECTOR(1024) NULL');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('concierge_knowledge_items');
    }
};
