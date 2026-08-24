<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $publication = static function (Blueprint $table): void {
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        };
        $heading = static function (Blueprint $table) use ($publication): void {
            $table->id();
            $table->string('eyebrow');
            $table->string('title');
            $table->text('description');
            $publication($table);
        };
        $item = static function (Blueprint $table): void {
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        };

        Schema::create('homepage_navbars', function (Blueprint $table) use ($publication) {
            $table->id();
            $table->string('logo_path');
            $table->string('logo_alt');
            $table->string('logo_href');
            $table->string('book_label');
            $table->string('book_href');
            $table->string('mobile_eyebrow');
            $table->string('mobile_open_label');
            $table->string('mobile_close_label');
            $publication($table);
        });
        Schema::create('homepage_navbar_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('navbar_id')->constrained('homepage_navbars')->cascadeOnDelete();
            $table->string('audience');
            $table->string('label');
            $table->string('href');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['navbar_id', 'audience', 'sort_order']);
        });
        Schema::create('homepage_brand_introductions', function (Blueprint $table) use ($publication) {
            $table->id();
            $table->string('title');
            $table->string('quote');
            $publication($table);
        });
        foreach (['words' => 'text', 'paragraphs' => 'body'] as $suffix => $column) {
            Schema::create("homepage_brand_introduction_$suffix", function (Blueprint $table) use ($suffix, $column) {
                $table->id();
                $table->unsignedBigInteger('brand_introduction_id');
                $table->foreign('brand_introduction_id', "hbi_{$suffix}_parent_fk")->references('id')->on('homepage_brand_introductions')->cascadeOnDelete();
                $table->unsignedTinyInteger('slot');
                $table->{$suffix === 'paragraphs' ? 'text' : 'string'}($column);
                $table->unique(['brand_introduction_id', 'slot'], "hbi_{$suffix}_slot_unique");
            });
        }
        Schema::create('homepage_brand_introduction_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_introduction_id');
            $table->foreign('brand_introduction_id', 'hbi_images_parent_fk')->references('id')->on('homepage_brand_introductions')->cascadeOnDelete();
            $table->unsignedTinyInteger('slot');
            $table->string('image_path');
            $table->string('image_alt');
            $table->unique(['brand_introduction_id', 'slot'], 'hbi_images_slot_unique');
        });

        $sections = [
            'featured_property_sections' => ['default_cta_label', 'scroll_label'], 'culinary_sections' => ['scroll_label'], 'wellness_sections' => [],
            'special_offer_sections' => ['all_offers_label', 'all_offers_href'], 'journal_sections' => ['explore_label', 'explore_href', 'read_label'], 'faq_sections' => [],
        ];
        foreach ($sections as $name => $extra) {
            Schema::create("homepage_$name", function (Blueprint $table) use ($heading, $extra) {
                $heading($table);
                foreach ($extra as $field) {
                    $table->string($field);
                }
            });
        }
        Schema::create('homepage_featured_in_sections', function (Blueprint $table) use ($publication) {
            $table->id();
            $table->string('title');
            $publication($table);
        });
        Schema::create('homepage_story_sections', function (Blueprint $table) use ($publication) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $publication($table);
        });
        Schema::create('homepage_memberships', function (Blueprint $table) use ($publication) {
            $table->id();
            $table->string('title');
            $table->string('subtitle');
            $table->text('description');
            $table->string('video_path')->nullable();
            $table->string('primary_label');
            $table->string('primary_href');
            $table->string('secondary_label');
            $table->string('secondary_href');
            $publication($table);
        });
        Schema::create('homepage_footers', function (Blueprint $table) use ($publication) {
            $table->id();
            foreach (['aria_label', 'background_image_path', 'logo_path', 'logo_alt', 'office_title', 'office_phone_label', 'office_phone_href', 'office_email_label', 'office_email_href', 'office_map_label', 'office_map_href', 'subscribe_title', 'subscribe_action_label', 'subscribe_action_href', 'socials_title', 'policy_label', 'policy_href', 'copyright'] as $field) {
                $table->string($field);
            } foreach (['summary', 'office_address', 'subscribe_description'] as $field) {
                $table->text($field);
            } $publication($table);
        });

        Schema::create('homepage_featured_properties', function (Blueprint $table) use ($item) {
            $table->id();
            $table->foreignId('section_id')->constrained('homepage_featured_property_sections')->cascadeOnDelete();
            foreach (['name', 'category', 'image_path', 'image_alt', 'href', 'cta_label'] as $field) {
                $table->string($field);
            } $table->text('description');
            $item($table);
            $table->index(['section_id', 'status', 'sort_order']);
        });
        Schema::create('homepage_culinary_destinations', function (Blueprint $table) use ($item) {
            $table->id();
            $table->foreignId('section_id')->constrained('homepage_culinary_sections')->cascadeOnDelete();
            foreach (['name', 'location', 'eyebrow', 'schedule', 'cta_label', 'image_path', 'image_alt', 'href'] as $field) {
                $table->string($field);
            } $table->text('description');
            $item($table);
        });
        Schema::create('homepage_wellness_escapes', function (Blueprint $table) use ($item) {
            $table->id();
            $table->foreignId('section_id')->constrained('homepage_wellness_sections')->cascadeOnDelete();
            foreach (['name', 'location', 'image_path', 'image_alt', 'href', 'cta_label'] as $field) {
                $table->string($field);
            } $table->text('description');
            $item($table);
        });
        Schema::create('homepage_wellness_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });
        Schema::create('homepage_wellness_escape_category', function (Blueprint $table) {
            $table->foreignId('wellness_escape_id')->constrained('homepage_wellness_escapes')->cascadeOnDelete();
            $table->foreignId('wellness_category_id')->constrained('homepage_wellness_categories')->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->primary(['wellness_escape_id', 'wellness_category_id']);
        });
        Schema::create('homepage_membership_benefits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membership_id')->constrained('homepage_memberships')->cascadeOnDelete();
            $table->string('label');
            $table->string('icon');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        Schema::create('homepage_story_blocks', function (Blueprint $table) use ($publication) {
            $table->id();
            $table->foreignId('section_id')->constrained('homepage_story_sections')->cascadeOnDelete();
            $table->unsignedTinyInteger('slot');
            foreach (['title', 'image_path', 'image_alt', 'cta_label', 'href'] as $field) {
                $table->string($field);
            } $table->text('description');
            $publication($table);
            $table->unique(['section_id', 'slot']);
        });
        Schema::create('homepage_special_offers', function (Blueprint $table) use ($publication) {
            $table->id();
            $table->foreignId('section_id')->constrained('homepage_special_offer_sections')->cascadeOnDelete();
            $table->unsignedTinyInteger('slot');
            foreach (['display_number', 'category', 'title', 'image_path', 'image_alt', 'href'] as $field) {
                $table->string($field);
            } $table->text('description');
            $publication($table);
            $table->unique(['section_id', 'slot']);
        });
        Schema::create('homepage_journal_stories', function (Blueprint $table) use ($item) {
            $table->id();
            $table->foreignId('section_id')->constrained('homepage_journal_sections')->cascadeOnDelete();
            foreach (['external_key', 'category', 'reading_time', 'image_path', 'image_alt', 'href'] as $field) {
                $table->string($field);
            } $table->text('description');
            $item($table);
            $table->unique(['section_id', 'external_key']);
        });
        Schema::create('homepage_journal_story_title_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('story_id')->constrained('homepage_journal_stories')->cascadeOnDelete();
            $table->unsignedInteger('line_number');
            $table->string('text');
            $table->unique(['story_id', 'line_number']);
        });
        Schema::create('homepage_featured_in_logos', function (Blueprint $table) use ($item) {
            $table->id();
            $table->foreignId('section_id')->constrained('homepage_featured_in_sections')->cascadeOnDelete();
            $table->string('image_path');
            $table->string('image_alt');
            $item($table);
        });
        Schema::create('homepage_faq_items', function (Blueprint $table) use ($item) {
            $table->id();
            $table->foreignId('section_id')->constrained('homepage_faq_sections')->cascadeOnDelete();
            $table->string('question');
            $table->text('answer');
            $item($table);
        });
        Schema::create('homepage_footer_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('footer_id')->constrained('homepage_footers')->cascadeOnDelete();
            $table->string('title');
            foreach (['phone_label', 'phone_href', 'email_label', 'email_href'] as $field) {
                $table->string($field)->nullable();
            } $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        Schema::create('homepage_footer_contact_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained('homepage_footer_contacts')->cascadeOnDelete();
            $table->string('label');
            $table->string('href');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        Schema::create('homepage_footer_socials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('footer_id')->constrained('homepage_footers')->cascadeOnDelete();
            $table->string('label');
            $table->string('href');
            $table->string('icon');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['homepage_footer_socials', 'homepage_footer_contact_actions', 'homepage_footer_contacts', 'homepage_faq_items', 'homepage_featured_in_logos', 'homepage_journal_story_title_lines', 'homepage_journal_stories', 'homepage_special_offers', 'homepage_story_blocks', 'homepage_membership_benefits', 'homepage_wellness_escape_category', 'homepage_wellness_categories', 'homepage_wellness_escapes', 'homepage_culinary_destinations', 'homepage_featured_properties', 'homepage_footers', 'homepage_memberships', 'homepage_story_sections', 'homepage_featured_in_sections', 'homepage_faq_sections', 'homepage_journal_sections', 'homepage_special_offer_sections', 'homepage_wellness_sections', 'homepage_culinary_sections', 'homepage_featured_property_sections', 'homepage_brand_introduction_images', 'homepage_brand_introduction_paragraphs', 'homepage_brand_introduction_words', 'homepage_brand_introductions', 'homepage_navbar_links', 'homepage_navbars'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
