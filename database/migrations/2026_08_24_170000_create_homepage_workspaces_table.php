<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const ROOT_TABLES = [
        'homepage_navbars',
        'homepage_brand_introductions',
        'homepage_featured_property_sections',
        'homepage_culinary_sections',
        'homepage_wellness_sections',
        'homepage_special_offer_sections',
        'homepage_journal_sections',
        'homepage_faq_sections',
        'homepage_featured_in_sections',
        'homepage_story_sections',
        'homepage_memberships',
        'homepage_footers',
    ];

    public function up(): void
    {
        Schema::create('homepage_workspaces', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->string('label');
            $table->timestamps();
        });

        DB::table('homepage_workspaces')->insert([
            ['key' => 'published', 'label' => 'Published', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'draft', 'label' => 'Draft', 'created_at' => now(), 'updated_at' => now()],
        ]);
        $publishedId = DB::table('homepage_workspaces')->where('key', 'published')->value('id');
        foreach (self::ROOT_TABLES as $table) {
            Schema::table($table, function (Blueprint $table): void {
                $table->foreignId('workspace_id')->nullable()->after('id');
                $table->index(['workspace_id', 'status']);
            });
            DB::table($table)->whereNull('workspace_id')->update(['workspace_id' => $publishedId]);
        }

        Schema::table('homepage_wellness_categories', function (Blueprint $table): void {
            $table->foreignId('workspace_id')->nullable()->after('id');
            $table->index('workspace_id');
        });
        DB::table('homepage_wellness_categories')->whereNull('workspace_id')->update(['workspace_id' => $publishedId]);
        Schema::table('homepage_wellness_categories', function (Blueprint $table): void {
            $table->dropUnique('homepage_wellness_categories_slug_unique');
            $table->unique(['workspace_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::table('homepage_wellness_categories', function (Blueprint $table): void {
            $table->dropUnique('homepage_wellness_categories_workspace_id_slug_unique');
            $table->unique('slug');
            $table->dropColumn('workspace_id');
        });
        foreach (self::ROOT_TABLES as $rootTable) {
            Schema::table($rootTable, function (Blueprint $table): void {
                $table->dropColumn('workspace_id');
            });
        }
        Schema::dropIfExists('homepage_workspaces');
    }
};
