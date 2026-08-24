<?php

namespace App\Services\Homepage;

use Illuminate\Support\Facades\DB;

class HomepageWorkspaceTransferService
{
    private const TREES = [
        'homepage_navbars' => ['homepage_navbar_links' => ['navbar_id', []]],
        'homepage_brand_introductions' => [
            'homepage_brand_introduction_words' => ['brand_introduction_id', []],
            'homepage_brand_introduction_paragraphs' => ['brand_introduction_id', []],
            'homepage_brand_introduction_images' => ['brand_introduction_id', []],
        ],
        'homepage_featured_property_sections' => ['homepage_featured_properties' => ['section_id', []]],
        'homepage_culinary_sections' => ['homepage_culinary_destinations' => ['section_id', []]],
        'homepage_wellness_sections' => ['homepage_wellness_escapes' => ['section_id', []]],
        'homepage_special_offer_sections' => ['homepage_special_offers' => ['section_id', []]],
        'homepage_journal_sections' => ['homepage_journal_stories' => ['section_id', [
            'homepage_journal_story_title_lines' => ['story_id', []],
        ]]],
        'homepage_faq_sections' => ['homepage_faq_items' => ['section_id', []]],
        'homepage_featured_in_sections' => ['homepage_featured_in_logos' => ['section_id', []]],
        'homepage_story_sections' => ['homepage_story_blocks' => ['section_id', []]],
        'homepage_memberships' => ['homepage_membership_benefits' => ['membership_id', []]],
        'homepage_footers' => [
            'homepage_footer_contacts' => ['footer_id', [
                'homepage_footer_contact_actions' => ['contact_id', []],
            ]],
            'homepage_footer_socials' => ['footer_id', []],
        ],
    ];

    /** @return array<string, array<int, array<string, mixed>>> */
    public function snapshot(string $workspace): array
    {
        $workspaceId = $this->workspaceId($workspace);
        $payload = [];
        foreach (self::TREES as $rootTable => $children) {
            $roots = DB::table($rootTable)->where('workspace_id', $workspaceId)->get()->map(fn (\stdClass $row) => $this->row($row))->all();
            $payload[$rootTable] = $roots;
            $this->collectChildren($payload, $children, array_column($roots, 'id'));
        }
        $payload['homepage_wellness_categories'] = DB::table('homepage_wellness_categories')->where('workspace_id', $workspaceId)->get()->map(fn (\stdClass $row) => $this->row($row))->all();
        $escapeIds = array_column($payload['homepage_wellness_escapes'] ?? [], 'id');
        $payload['homepage_wellness_escape_category'] = $escapeIds === [] ? [] : DB::table('homepage_wellness_escape_category')->whereIn('wellness_escape_id', $escapeIds)->get()->map(fn (\stdClass $row) => $this->row($row))->all();

        return $payload;
    }

    public function replace(string $source, string $target, bool $publishDrafts = false): void
    {
        $this->restore($this->snapshot($source), $target, $publishDrafts);
    }

    /** @param array<string, array<int, array<string, mixed>>> $payload */
    public function restore(array $payload, string $target, bool $publishDrafts = false): void
    {
        $targetId = $this->workspaceId($target);
        foreach (array_keys(self::TREES) as $rootTable) {
            DB::table($rootTable)->where('workspace_id', $targetId)->delete();
        }
        DB::table('homepage_wellness_categories')->where('workspace_id', $targetId)->delete();

        $maps = [];
        foreach (self::TREES as $rootTable => $children) {
            $maps[$rootTable] = $this->insertRows($rootTable, $payload[$rootTable] ?? [], null, [], $targetId, $publishDrafts);
            $this->restoreChildren($payload, $children, $maps[$rootTable], $maps, $publishDrafts);
        }
        $maps['homepage_wellness_categories'] = $this->insertRows('homepage_wellness_categories', $payload['homepage_wellness_categories'] ?? [], null, [], $targetId, false);

        foreach ($payload['homepage_wellness_escape_category'] ?? [] as $pivot) {
            $escape = $maps['homepage_wellness_escapes'][$pivot['wellness_escape_id']] ?? null;
            $category = $maps['homepage_wellness_categories'][$pivot['wellness_category_id']] ?? null;
            if ($escape && $category) {
                DB::table('homepage_wellness_escape_category')->insert([
                    'wellness_escape_id' => $escape,
                    'wellness_category_id' => $category,
                    'sort_order' => $pivot['sort_order'],
                ]);
            }
        }
    }

    /**
     * @param  array<string, array<int, array<string, mixed>>>  $payload
     * @return list<string>
     */
    public function mediaPaths(array $payload): array
    {
        $paths = [];
        array_walk_recursive($payload, function ($value) use (&$paths): void {
            if (is_string($value) && str_starts_with($value, 'homepage/')) {
                $paths[$value] = true;
            }
        });

        return array_keys($paths);
    }

    /**
     * @param  array<string, array<int, array<string, mixed>>>  $payload
     *
     * @param-out array<string, array<int, array<string, mixed>>> $payload
     *
     * @param  array<string, mixed>  $children
     * @param  list<int>  $parentIds
     */
    private function collectChildren(array &$payload, array $children, array $parentIds): void
    {
        foreach ($children as $table => [$foreignKey, $nested]) {
            $rows = $parentIds === [] ? [] : DB::table($table)->whereIn($foreignKey, $parentIds)->get()->map(fn (\stdClass $row) => $this->row($row))->all();
            $payload[$table] = [...($payload[$table] ?? []), ...$rows];
            $this->collectChildren($payload, $nested, array_column($rows, 'id'));
        }
    }

    /**
     * @param  array<string, array<int, array<string, mixed>>>  $payload
     * @param  array<string, mixed>  $children
     * @param  array<int, int>  $parentMap
     * @param  array<string, array<int, int>>  $maps
     */
    private function restoreChildren(array $payload, array $children, array $parentMap, array &$maps, bool $publishDrafts): void
    {
        foreach ($children as $table => [$foreignKey, $nested]) {
            $rows = array_values(array_filter($payload[$table] ?? [], fn ($row) => isset($parentMap[$row[$foreignKey]])));
            $maps[$table] = $this->insertRows($table, $rows, $foreignKey, $parentMap, null, $publishDrafts);
            $this->restoreChildren($payload, $nested, $maps[$table], $maps, $publishDrafts);
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     * @param  array<int, int>  $parentMap
     * @return array<int, int>
     */
    private function insertRows(string $table, array $rows, ?string $foreignKey, array $parentMap, ?int $workspaceId, bool $publishDrafts): array
    {
        $map = [];
        foreach ($rows as $row) {
            $oldId = $row['id'] ?? null;
            unset($row['id']);
            if ($foreignKey !== null) {
                $row[$foreignKey] = $parentMap[$row[$foreignKey]];
            }
            if ($workspaceId !== null) {
                $row['workspace_id'] = $workspaceId;
            }
            if ($publishDrafts && ($row['status'] ?? null) === 'draft') {
                $row['status'] = 'published';
                $row['published_at'] = now();
            }
            $newId = DB::table($table)->insertGetId($row);
            if ($oldId !== null) {
                $map[$oldId] = $newId;
            }
        }

        return $map;
    }

    private function workspaceId(string $key): int
    {
        return (int) DB::table('homepage_workspaces')->where('key', $key)->value('id');
    }

    /** @return array<string, mixed> */
    private function row(\stdClass $row): array
    {
        return get_object_vars($row);
    }
}
