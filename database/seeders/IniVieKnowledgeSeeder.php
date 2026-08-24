<?php

namespace Database\Seeders;

use App\Jobs\Concierge\ImportIniVieKnowledgeChunk;
use Illuminate\Console\Command;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Bus;
use JsonException;
use RuntimeException;

class IniVieKnowledgeSeeder extends Seeder
{
    /** @throws JsonException */
    public function run(): void
    {
        $path = database_path('data/inivie-knowledge.json');
        $contents = file_get_contents($path);

        if ($contents === false) {
            throw new RuntimeException("Unable to read knowledge fixture: {$path}");
        }

        /** @var list<array{title: string, category: string, content: string, source_url: string}> $entries */
        $entries = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);

        $jobs = array_map(
            fn (array $chunk): ImportIniVieKnowledgeChunk => new ImportIniVieKnowledgeChunk($chunk),
            array_chunk($entries, 20),
        );

        $batch = Bus::batch($jobs)->name('Import Ini Vie knowledge')->dispatch();

        $command = get_object_vars($this)['command'] ?? null;

        if ($command instanceof Command) {
            $command->info("Ini Vie knowledge batch dispatched: {$batch->id}");
        }
    }
}
