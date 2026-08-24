<?php

use App\Http\Controllers\Homepage\BrandIntroductionController;
use App\Http\Controllers\Homepage\CulinaryController;
use App\Http\Controllers\Homepage\FaqController;
use App\Http\Controllers\Homepage\FeaturedInController;
use App\Http\Controllers\Homepage\FeaturedPropertiesController;
use App\Http\Controllers\Homepage\FooterController;
use App\Http\Controllers\Homepage\MembershipController;
use App\Http\Controllers\Homepage\NavbarController;
use App\Http\Controllers\Homepage\OurStoryController;
use App\Http\Controllers\Homepage\SpecialOffersController;
use App\Http\Controllers\Homepage\WellnessController;
use App\Http\Controllers\Homepage\WhatsNewController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('cms/homepage')->name('homepage.')->group(function (): void {
    Route::get('navbar', [NavbarController::class, 'edit'])->name('navbar.edit');
    Route::put('navbar', [NavbarController::class, 'update'])->name('navbar.update');
    Route::post('navbar/links', [NavbarController::class, 'saveLink'])->name('navbar.links.store');
    Route::put('navbar/links/{item}', [NavbarController::class, 'saveLink'])->name('navbar.links.update');
    Route::delete('navbar/links/{item}', [NavbarController::class, 'deleteLink'])->name('navbar.links.destroy');

    Route::get('brand-introduction', [BrandIntroductionController::class, 'edit'])->name('brand-introduction.edit');
    Route::put('brand-introduction', [BrandIntroductionController::class, 'update'])->name('brand-introduction.update');

    $collections = [
        'featured-properties' => FeaturedPropertiesController::class, 'culinary' => CulinaryController::class,
        'wellness' => WellnessController::class, 'whats-new' => WhatsNewController::class,
        'featured-in' => FeaturedInController::class, 'faq' => FaqController::class,
    ];
    foreach ($collections as $section => $controller) {
        Route::get($section, [$controller, 'index'])->name("$section.index");
        Route::put($section, [$controller, 'update'])->name("$section.update")->defaults('section', $section);
        Route::get("$section/items/create", [$controller, 'create'])->name("$section.items.create")->defaults('section', $section);
        Route::get("$section/items/{item}/edit", [$controller, 'edit'])->name("$section.items.edit")->defaults('section', $section);
        Route::post("$section/items", [$controller, 'store'])->name("$section.items.store")->defaults('section', $section);
        Route::put("$section/items/{item}", [$controller, 'updateItem'])->name("$section.items.update")->defaults('section', $section);
        Route::delete("$section/items/{item}", [$controller, 'destroy'])->name("$section.items.destroy")->defaults('section', $section);
    }

    foreach (['membership' => MembershipController::class, 'our-story' => OurStoryController::class, 'special-offers' => SpecialOffersController::class, 'footer' => FooterController::class] as $section => $controller) {
        Route::get($section, [$controller, 'index'])->name("$section.index");
        Route::put($section, [$controller, 'update'])->name("$section.update")->defaults('section', $section);
    }

    Route::post('membership/benefits', [MembershipController::class, 'saveBenefit'])->name('membership.benefits.store');
    Route::put('membership/benefits/{item}', [MembershipController::class, 'saveBenefit'])->name('membership.benefits.update');
    Route::delete('membership/benefits/{item}', [MembershipController::class, 'deleteBenefit'])->name('membership.benefits.destroy');
    Route::put('our-story/blocks/{item}', [OurStoryController::class, 'updateBlock'])->name('our-story.blocks.update');
    Route::put('special-offers/items/{item}', [SpecialOffersController::class, 'updateOffer'])->name('special-offers.items.update');
    Route::post('footer/contacts', [FooterController::class, 'saveContact'])->name('footer.contacts.store');
    Route::put('footer/contacts/{item}', [FooterController::class, 'saveContact'])->name('footer.contacts.update');
    Route::delete('footer/contacts/{item}', [FooterController::class, 'deleteContact'])->name('footer.contacts.destroy');
    Route::post('footer/socials', [FooterController::class, 'saveSocial'])->name('footer.socials.store');
    Route::put('footer/socials/{item}', [FooterController::class, 'saveSocial'])->name('footer.socials.update');
    Route::delete('footer/socials/{item}', [FooterController::class, 'deleteSocial'])->name('footer.socials.destroy');
});
