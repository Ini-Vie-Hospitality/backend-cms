<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_footers', function (Blueprint $table): void {
            $table->id();
            $table->string('aria_label');
            $table->string('background_image_path');
            $table->string('logo_path');
            $table->string('logo_alt');
            $table->text('summary');
            $table->string('office_title');
            $table->text('office_address');
            $table->string('office_phone_label');
            $table->string('office_phone_href');
            $table->string('office_email_label');
            $table->string('office_email_href');
            $table->string('office_map_label');
            $table->string('office_map_href');
            $table->string('subscribe_title');
            $table->text('subscribe_description');
            $table->string('subscribe_action_label');
            $table->string('subscribe_action_href');
            $table->string('socials_title');
            $table->string('policy_label');
            $table->string('policy_href');
            $table->string('copyright');
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_footers');
    }
};
