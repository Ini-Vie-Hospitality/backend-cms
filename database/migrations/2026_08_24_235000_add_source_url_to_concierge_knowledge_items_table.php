<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('concierge_knowledge_items', function (Blueprint $table): void {
            $table->string('source_url', 500)->nullable()->unique()->after('content');
        });
    }

    public function down(): void
    {
        Schema::table('concierge_knowledge_items', function (Blueprint $table): void {
            $table->dropUnique(['source_url']);
            $table->dropColumn('source_url');
        });
    }
};
