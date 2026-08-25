<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_popups', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('workspace_id')->nullable()->index();
            $table->string('image_path')->nullable();
            $table->string('image_alt')->default('Website announcement');
            $table->string('redirect_url');
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        foreach (['published', 'draft'] as $workspace) {
            DB::table('homepage_popups')->insert([
                'workspace_id' => DB::table('homepage_workspaces')->where('key', $workspace)->value('id'),
                'redirect_url' => 'https://inivie.com',
                'status' => $workspace,
                'published_at' => $workspace === 'published' ? now() : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_popups');
    }
};
