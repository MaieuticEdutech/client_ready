<?php

use App\Models\Sample;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake(config('filesystems.films'));
    Storage::disk(config('filesystems.films'))->put('films/demo.mp4', str_repeat('x', 1000));
});

it('streams a film with byte-range support', function () {
    $this->get('/films/demo.mp4', ['Range' => 'bytes=100-199'])
        ->assertStatus(206)
        ->assertHeader('Content-Range', 'bytes 100-199/1000')
        ->assertHeader('Accept-Ranges', 'bytes')
        ->assertHeader('Content-Type', 'video/mp4');
});

it('answers a small film in one piece even without a range', function () {
    $this->get('/films/demo.mp4')
        ->assertStatus(206)
        ->assertHeader('Content-Range', 'bytes 0-999/1000')
        ->assertHeader('Content-Length', '1000');
});

it('caps an open-ended range so no request outlives a moment', function () {
    $twelveMegabytes = 12 * 1024 * 1024;
    Storage::disk(config('filesystems.films'))->put('films/long.mp4', str_repeat('y', $twelveMegabytes));

    $eightMegabytes = 8 * 1024 * 1024;

    $this->get('/films/long.mp4', ['Range' => 'bytes=0-'])
        ->assertStatus(206)
        ->assertHeader('Content-Range', 'bytes 0-'.($eightMegabytes - 1).'/'.$twelveMegabytes)
        ->assertHeader('Content-Length', (string) $eightMegabytes);

    // The browser asks for the rest and gets it.
    $this->get('/films/long.mp4', ['Range' => 'bytes='.$eightMegabytes.'-'])
        ->assertStatus(206)
        ->assertHeader('Content-Range', 'bytes '.$eightMegabytes.'-'.($twelveMegabytes - 1).'/'.$twelveMegabytes);
});

it('refuses a film that does not exist', function () {
    $this->get('/films/missing.mp4')->assertNotFound();
});

it('never reaches outside the films folder', function () {
    $this->get('/films/..%2F..%2F.env')->assertNotFound();
});

it('points a local upload at the streaming route', function () {
    $sample = Sample::factory()->uploaded()->create(['video_path' => 'films/demo.mp4']);

    expect($sample->source())->toBe(route('films.show', ['path' => 'demo.mp4']));
});
