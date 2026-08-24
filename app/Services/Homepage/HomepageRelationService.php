<?php

namespace App\Services\Homepage;

use App\Http\Requests\Homepage\SaveFooterContactRequest;
use App\Http\Requests\Homepage\SaveFooterSocialRequest;
use App\Http\Requests\Homepage\SaveMembershipBenefitRequest;
use App\Http\Requests\Homepage\SaveNavbarLinkRequest;
use App\Http\Requests\Homepage\UpdateSpecialOfferRequest;
use App\Http\Requests\Homepage\UpdateStoryBlockRequest;
use App\Services\HomepageMediaService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class HomepageRelationService
{
    public function __construct(private HomepageMediaService $media) {}

    public function saveNavbarLink(SaveNavbarLinkRequest $request, ?int $item): void
    {
        $this->saveRelated('homepage_navbar_links', 'navbar_id', 'homepage_navbars', $request, $item);
    }

    public function saveBenefit(SaveMembershipBenefitRequest $request, ?int $item): void
    {
        $this->saveRelated('homepage_membership_benefits', 'membership_id', 'homepage_memberships', $request, $item);
    }

    public function saveFooterContact(SaveFooterContactRequest $request, ?int $item): void
    {
        $this->saveRelated('homepage_footer_contacts', 'footer_id', 'homepage_footers', $request, $item);
    }

    public function saveFooterSocial(SaveFooterSocialRequest $request, ?int $item): void
    {
        $this->saveRelated('homepage_footer_socials', 'footer_id', 'homepage_footers', $request, $item);
    }

    public function updateStoryBlock(UpdateStoryBlockRequest $request, int $item): void
    {
        $this->updateMediaRecord('homepage_story_blocks', $request, $item);
    }

    public function updateOffer(UpdateSpecialOfferRequest $request, int $item): void
    {
        $this->updateMediaRecord('homepage_special_offers', $request, $item);
    }

    public function delete(string $table, int $item): void
    {
        abort_unless(DB::table($table)->where('id', $item)->delete() > 0, 404);
    }

    private function saveRelated(string $table, string $foreignKey, string $parentTable, FormRequest $request, ?int $item): void
    {
        $data = $request->validated();
        if ($item !== null) {
            abort_unless(DB::table($table)->where('id', $item)->exists(), 404);
            DB::table($table)->where('id', $item)->update($data);

            return;
        }
        $data[$foreignKey] = DB::table($parentTable)->value('id') ?? abort(404);
        DB::table($table)->insert($data + ['created_at' => now(), 'updated_at' => now()]);
    }

    private function updateMediaRecord(string $table, FormRequest $request, int $item): void
    {
        $data = $request->validated();
        abort_unless(DB::table($table)->where('id', $item)->exists(), 404);
        if ($request->hasFile('image')) {
            $data['image_path'] = $this->media->store($request->file('image'));
        }
        unset($data['image']);
        $data['published_at'] = $data['status'] === 'published' ? now() : null;
        DB::table($table)->where('id', $item)->update($data);
    }
}
