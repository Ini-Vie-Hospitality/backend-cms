<?php

namespace App\Services\Homepage;

use App\Http\Requests\Homepage\UpdateBrandIntroductionRequest;
use App\Services\HomepageMediaService;
use Illuminate\Support\Facades\DB;

class BrandIntroductionService
{
    public function __construct(private HomepageMediaService $media) {}

    public function update(UpdateBrandIntroductionRequest $request): void
    {
        $data = $request->validated();
        $record = DB::table('homepage_brand_introductions')->firstOrFail();

        DB::transaction(function () use ($data, $record, $request): void {
            DB::table('homepage_brand_introductions')->where('id', $record->id)->update(['title' => $data['title'], 'quote' => $data['quote'], 'status' => $data['status'], 'published_at' => $data['status'] === 'published' ? now() : null]);
            foreach ([1, 2] as $slot) {
                DB::table('homepage_brand_introduction_words')->where(['brand_introduction_id' => $record->id, 'slot' => $slot])->update(['text' => $data["word_$slot"]]);
                DB::table('homepage_brand_introduction_paragraphs')->where(['brand_introduction_id' => $record->id, 'slot' => $slot])->update(['body' => $data["paragraph_$slot"]]);
            }
            foreach ([1, 2, 3] as $slot) {
                $image = ['image_alt' => $data["image_alt_$slot"]];
                if ($request->hasFile("image_$slot")) {
                    $image['image_path'] = $this->media->store($request->file("image_$slot"));
                }
                DB::table('homepage_brand_introduction_images')->where(['brand_introduction_id' => $record->id, 'slot' => $slot])->update($image);
            }
        });
    }
}
