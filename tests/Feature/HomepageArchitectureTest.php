<?php

use Illuminate\Support\Facades\File;

test('homepage controllers stay thin', function () {
    $files = File::allFiles(app_path('Http/Controllers/Homepage'));

    expect($files)->not->toBeEmpty();

    foreach ($files as $file) {
        $source = $file->getContents();

        expect($source)
            ->not->toContain('DB::')
            ->not->toContain('->validate(');
    }
});

test('homepage API uses Laravel resources', function () {
    expect(File::exists(app_path('Http/Resources/Homepage/HomepageResource.php')))->toBeTrue();
});

test('homepage migrations create one table per file', function () {
    $files = collect(File::files(database_path('migrations')))
        ->filter(fn ($file) => str_contains($file->getFilename(), 'homepage'));

    expect($files->count())->toBeGreaterThan(20);

    foreach ($files as $file) {
        expect(substr_count($file->getContents(), 'Schema::create('))->toBeLessThanOrEqual(1);
    }
});
