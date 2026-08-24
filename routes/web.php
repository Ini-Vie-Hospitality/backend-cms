<?php

use App\Http\Controllers\RelationalHomepageController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
    Route::prefix('cms/homepage')->name('homepage.')->controller(RelationalHomepageController::class)->group(function () {
        Route::get('navbar', 'navbar')->name('navbar.edit');
        Route::put('navbar', 'updateNavbar')->name('navbar.update');
        Route::get('brand-introduction', 'brandIntroduction')->name('brand-introduction.edit');
        Route::put('brand-introduction', 'updateBrandIntroduction')->name('brand-introduction.update');
        Route::post('navbar/links', 'saveNavbarLink')->name('navbar.links.store');
        Route::put('navbar/links/{item}', 'saveNavbarLink')->name('navbar.links.update');
        Route::delete('navbar/links/{item}', 'deleteNavbarLink')->name('navbar.links.destroy');
        Route::post('membership/benefits', 'saveBenefit')->name('membership.benefits.store');
        Route::put('membership/benefits/{item}', 'saveBenefit')->name('membership.benefits.update');
        Route::delete('membership/benefits/{item}', 'deleteBenefit')->name('membership.benefits.destroy');
        Route::put('our-story/blocks/{item}', 'updateStoryBlock')->name('our-story.blocks.update');
        Route::put('special-offers/items/{item}', 'updateOffer')->name('special-offers.items.update');
        Route::post('footer/contacts', 'saveFooterContact')->name('footer.contacts.store');
        Route::put('footer/contacts/{item}', 'saveFooterContact')->name('footer.contacts.update');
        Route::delete('footer/contacts/{item}', 'deleteFooterContact')->name('footer.contacts.destroy');
        Route::post('footer/socials', 'saveFooterSocial')->name('footer.socials.store');
        Route::put('footer/socials/{item}', 'saveFooterSocial')->name('footer.socials.update');
        Route::delete('footer/socials/{item}', 'deleteFooterSocial')->name('footer.socials.destroy');
        foreach (['featured-properties' => 'featuredProperties', 'culinary' => 'culinary', 'wellness' => 'wellness', 'membership' => 'membership', 'our-story' => 'ourStory', 'special-offers' => 'specialOffers', 'whats-new' => 'whatsNew', 'featured-in' => 'featuredIn', 'faq' => 'faq', 'footer' => 'footer'] as $uri => $action) {
            Route::get($uri, $action)->name("$uri.index");
        }
        foreach (['featured-properties', 'culinary', 'wellness', 'membership', 'our-story', 'special-offers', 'whats-new', 'featured-in', 'faq', 'footer'] as $section) {
            Route::put($section, 'updateSection')->name("$section.update")->defaults('section', $section);
        }
        foreach (['featured-properties', 'culinary', 'wellness', 'whats-new', 'featured-in', 'faq'] as $section) {
            Route::get("$section/items/create", 'createItem')->name("$section.items.create")->defaults('section', $section);
            Route::get("$section/items/{item}/edit", 'editItem')->name("$section.items.edit")->defaults('section', $section);
            Route::post("$section/items", 'storeItem')->name("$section.items.store")->defaults('section', $section);
            Route::put("$section/items/{item}", 'updateItem')->name("$section.items.update")->defaults('section', $section);
            Route::delete("$section/items/{item}", 'deleteItem')->name("$section.items.destroy")->defaults('section', $section);
        }
    });
});

require __DIR__.'/settings.php';
