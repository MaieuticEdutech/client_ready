<?php

use App\Models\Sample;
use App\Models\Service;

it('lists every published service in order', function () {
    Service::factory()->create(['name' => 'Storyboarding', 'sort_order' => 1]);
    Service::factory()->create(['name' => 'Articulate', 'sort_order' => 0]);

    $this->get('/')
        ->assertOk()
        ->assertSeeInOrder(['Articulate', 'Storyboarding']);
});

it('hides an unpublished service', function () {
    Service::factory()->create(['name' => 'Visible Line']);
    Service::factory()->unpublished()->create(['name' => 'Hidden Line']);

    $this->get('/')
        ->assertSee('Visible Line')
        ->assertDontSee('Hidden Line');
});

it('shows a service its own samples in order', function () {
    $service = Service::factory()->create(['name' => '2D Animation']);
    Sample::factory()->for($service)->create(['title' => 'Second Piece', 'sort_order' => 2]);
    Sample::factory()->for($service)->create(['title' => 'First Piece', 'sort_order' => 1]);

    $this->get('/')->assertSeeInOrder(['First Piece', 'Second Piece']);
});

it('says plainly when a sample has no footage', function () {
    $service = Service::factory()->create();
    Sample::factory()->for($service)->create(['title' => 'Pending Piece']);

    $this->get('/')->assertSee('Footage coming soon');
});

it('plays an uploaded film on the card', function () {
    $service = Service::factory()->create();
    Sample::factory()->for($service)->uploaded()->create();

    $this->get('/')
        ->assertSee('playsinline', false)
        ->assertDontSee('Footage coming soon');
});

it('embeds a pasted youtube link instead of a video tag', function () {
    $service = Service::factory()->create();
    Sample::factory()->for($service)->youtube()->create();

    $this->get('/')->assertSee('youtube.com/embed/dQw4w9WgXcQ', false);
});

it('offers a jump link per service', function () {
    Service::factory()->create(['name' => 'Motion Graphics']);

    $this->get('/')->assertSee('href="#motion-graphics"', false);
});

it('loads services and samples without a query per section', function () {
    Service::factory()->count(6)->create()->each(
        fn ($service) => Sample::factory()->count(3)->for($service)->create()
    );

    DB::enableQueryLog();
    $this->get('/')->assertOk();
    $queries = count(DB::getQueryLog());
    DB::disableQueryLog();

    // Eager loaded: section count must not drive query count.
    expect($queries)->toBeLessThan(8);
});

it('reveals content without javascript for reduced-motion users', function () {
    Service::factory()->create();

    // The stylesheet carries the opt-out, so assert it covers the hook.
    expect(file_get_contents(resource_path('css/app.css')))
        ->toContain('prefers-reduced-motion')
        ->toContain('.reveal { opacity: 1; transform: none; transition: none; }');
});
