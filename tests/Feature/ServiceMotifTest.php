<?php

use App\Models\Sample;
use App\Models\Service;
use Illuminate\Support\Facades\Blade;

it('gives each discipline its own motif', function (string $name, string $marker) {
    $service = Service::factory()->create(['name' => $name]);

    expect(Blade::render('<x-service-motif :service="$service" />', ['service' => $service]))
        ->toContain($marker);
})->with([
    ['Articulate', 'motif-branch'],
    ['Infographics', 'motif-arc'],
    ['Animation', 'motif-frame'],
    ['AI Videos', 'motif-cell'],
    ['Smart Board', 'motif-panel'],
    ['Motion Graphics', 'motif-bar'],
]);

it('tints a motif with its own service colours', function () {
    $service = Service::factory()->create(['accent_from' => '#69FFF7', 'accent_to' => '#008680']);

    expect(Blade::render('<x-service-motif :service="$service" />', ['service' => $service]))
        ->toContain('--from: #69FFF7')
        ->toContain('--to: #008680');
});

it('keeps motifs out of the accessibility tree', function () {
    $service = Service::factory()->create();

    expect(Blade::render('<x-service-motif :service="$service" />', ['service' => $service]))
        ->toContain('aria-hidden="true"');
});

it('renders a motif for every section on the page', function () {
    Service::factory()->count(6)->create();

    $html = $this->get('/')->getContent();

    expect(substr_count($html, 'motif-splash'))->toBe(6);
});

it('lacquers every card with a gloss layer', function () {
    $service = Service::factory()->create();
    Sample::factory()->for($service)->count(2)->create();

    $html = $this->get('/')->getContent();

    expect(substr_count($html, 'sample-gloss'))->toBe(2)
        ->and(substr_count($html, 'sample-shine'))->toBe(2);
});

it('washes a card with its service colour under the cursor', function () {
    $service = Service::factory()->create(['accent_from' => '#F8847E']);
    Sample::factory()->for($service)->create();

    $this->get('/')
        ->assertSee('sample-splash', false)
        ->assertSee('--from: #F8847E', false)
        // Position is written to custom properties, not to component state.
        ->assertSee("setProperty('--x'", false);
});

it('stills every motif for reduced-motion users', function () {
    $css = file_get_contents(resource_path('css/app.css'));

    $block = substr($css, strpos($css, 'prefers-reduced-motion'));

    foreach (['motif-ring', 'motif-arc', 'motif-frame', 'motif-cell', 'motif-panel', 'motif-bar', 'sample-shine'] as $hook) {
        expect($block)->toContain($hook);
    }
});
