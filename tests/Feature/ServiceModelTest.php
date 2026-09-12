<?php

use App\Models\Sample;
use App\Models\Service;
use Database\Seeders\ServiceSeeder;

it('slugs a service from its name', function () {
    expect(Service::factory()->create(['name' => '3D Animation'])->slug)->toBe('3d-animation');
});

it('builds a gradient from its own two stops', function () {
    $service = Service::factory()->create(['accent_from' => '#15D9A1', 'accent_to' => '#00615C']);

    expect($service->gradient())->toBe('linear-gradient(135deg, #15D9A1, #00615C)');
});

it('prefers an uploaded file over a pasted link', function () {
    $sample = Sample::factory()->youtube()->uploaded()->create();

    expect($sample->isUploaded())->toBeTrue()
        ->and($sample->embedUrl())->toBeNull();
});

it('builds embed urls for known providers', function (string $url, ?string $expected) {
    expect(Sample::factory()->create(['video_url' => $url])->embedUrl())->toBe($expected);
})->with([
    ['https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
    ['https://youtu.be/dQw4w9WgXcQ', 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
    ['https://vimeo.com/76979871', 'https://player.vimeo.com/video/76979871'],
    ['https://example.com/film.mov', null],
]);

it('deletes samples with their service', function () {
    $service = Service::factory()->has(Sample::factory()->count(3))->create();

    $service->delete();

    expect(Sample::count())->toBe(0);
});

it('seeds the seven lines of work exactly once', function () {
    $this->seed(ServiceSeeder::class);
    $this->seed(ServiceSeeder::class);

    expect(Service::count())->toBe(7)
        ->and(Sample::count())->toBe(24);
});
