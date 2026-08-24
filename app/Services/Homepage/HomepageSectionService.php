<?php

namespace App\Services\Homepage;

use App\Http\Requests\Homepage\UpdateNavbarRequest;
use App\Http\Requests\Homepage\UpdateSectionRequest;
use App\Services\HomepageMediaService;
use App\Support\HomepageDefinitions;
use Illuminate\Support\Facades\DB;

class HomepageSectionService
{
    public function __construct(private HomepageMediaService $media) {}

    public function update(string $section, UpdateSectionRequest $request): void
    {
        $definition = HomepageDefinitions::section($section);
        $data = $request->validated();
        $row = DB::table($definition['table'])->firstOrFail();

        foreach ($definition['media'] as $field => $type) {
            if ($request->hasFile($field)) {
                $data[$field.'_path'] = $this->media->store($request->file($field));
            }
            unset($data[$field]);
        }
        $data['published_at'] = $data['status'] === 'published' ? now() : null;
        DB::table($definition['table'])->where('id', $row->id)->update($data);
    }

    public function updateNavbar(UpdateNavbarRequest $request): void
    {
        $data = $request->validated();
        $row = DB::table('homepage_navbars')->firstOrFail();
        if ($request->hasFile('logo')) {
            $data['logo_path'] = $this->media->store($request->file('logo'));
        }
        unset($data['logo']);
        $data['published_at'] = $data['status'] === 'published' ? now() : null;
        DB::table('homepage_navbars')->where('id', $row->id)->update($data);
    }
}
