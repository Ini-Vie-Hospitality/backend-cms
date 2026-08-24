<?php

namespace App\Services\Homepage;

use App\Http\Requests\Homepage\SaveItemRequest;
use App\Services\HomepageMediaService;
use App\Support\HomepageDefinitions;
use Illuminate\Support\Facades\DB;

class HomepageItemService
{
    public function __construct(private HomepageMediaService $media) {}

    public function save(string $section, SaveItemRequest $request, ?int $item = null): void
    {
        $definition = HomepageDefinitions::item($section);
        $data = $request->validated();
        if ($definition['image'] && $request->hasFile('image')) {
            $data['image_path'] = $this->media->store($request->file('image'));
        }
        unset($data['image']);
        $data['published_at'] = $data['status'] === 'published' ? now() : null;

        if ($item !== null) {
            abort_unless(DB::table($definition['table'])->where('id', $item)->exists(), 404);
            DB::table($definition['table'])->where('id', $item)->update($data);

            return;
        }
        $data['section_id'] = DB::table($definition['parent'])->value('id') ?? abort(404);
        DB::table($definition['table'])->insert($data + ['created_at' => now(), 'updated_at' => now()]);
    }

    public function delete(string $section, int $item): void
    {
        $definition = HomepageDefinitions::item($section);
        abort_unless(DB::table($definition['table'])->where('id', $item)->delete() > 0, 404);
    }
}
