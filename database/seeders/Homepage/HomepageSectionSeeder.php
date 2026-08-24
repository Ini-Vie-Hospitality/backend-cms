<?php

namespace Database\Seeders\Homepage;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

abstract class HomepageSectionSeeder extends Seeder
{
    /** @return array<string, mixed> */
    protected function published(): array
    {
        $now = now();

        return ['status' => 'published', 'published_at' => $now, 'created_at' => $now, 'updated_at' => $now];
    }

    /** @param array<string, mixed> $extra */
    protected function heading(string $table, string $eyebrow, string $title, string $description, array $extra = []): int
    {
        return DB::table($table)->insertGetId(['eyebrow' => $eyebrow, 'title' => $title, 'description' => $description, ...$extra, ...$this->published()]);
    }
}
