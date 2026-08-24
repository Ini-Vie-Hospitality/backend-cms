<?php

namespace App\Services\Homepage\Content;

use Illuminate\Support\Facades\DB;

class FooterContentService
{
    public function __construct(private ContentMedia $media) {}

    /** @return array<string, mixed>|null */
    public function footer(): ?array
    {
        $row = DB::table('homepage_footers')->where('status', 'published')->whereNotNull('published_at')->first();
        if (! $row) {
            return null;
        }

        $contacts = DB::table('homepage_footer_contacts')->where('footer_id', $row->id)->where('is_active', true)->orderBy('sort_order')->get()->map(function ($contact) {
            $data = ['title' => $contact->title, 'actions' => DB::table('homepage_footer_contact_actions')->where('contact_id', $contact->id)->where('is_active', true)->orderBy('sort_order')->get()->map(fn ($action) => ['label' => $action->label, 'href' => $action->href])->all()];
            if ($contact->phone_label) {
                $data['phone'] = ['label' => $contact->phone_label, 'href' => $contact->phone_href];
            }
            if ($contact->email_label) {
                $data['email'] = ['label' => $contact->email_label, 'href' => $contact->email_href];
            }

            return $data;
        })->all();
        $socials = DB::table('homepage_footer_socials')->where('footer_id', $row->id)->where('is_active', true)->orderBy('sort_order')->get()->map(fn ($item) => ['label' => $item->label, 'href' => $item->href, 'icon' => $item->icon])->all();

        return ['ariaLabel' => $row->aria_label, 'backgroundImage' => $this->media->url($row->background_image_path), 'logo' => ['src' => $this->media->url($row->logo_path), 'alt' => $row->logo_alt], 'summary' => $row->summary, 'office' => ['title' => $row->office_title, 'address' => $row->office_address, 'phone' => ['label' => $row->office_phone_label, 'href' => $row->office_phone_href], 'email' => ['label' => $row->office_email_label, 'href' => $row->office_email_href], 'map' => ['label' => $row->office_map_label, 'href' => $row->office_map_href]], 'subscribe' => ['title' => $row->subscribe_title, 'description' => $row->subscribe_description, 'action' => ['label' => $row->subscribe_action_label, 'href' => $row->subscribe_action_href]], 'contacts' => $contacts, 'socialsTitle' => $row->socials_title, 'socials' => $socials, 'policy' => ['label' => $row->policy_label, 'href' => $row->policy_href], 'copyright' => $row->copyright];
    }
}
