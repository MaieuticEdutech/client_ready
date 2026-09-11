<?php

use App\Models\Sample;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

it('redirects guests away from the samples screen', function () {
    auth()->logout();

    $this->get('/studio/samples')->assertRedirect(route('admin.login'));
});

it('lists every sample grouped under its service', function () {
    $service = Service::factory()->create(['name' => 'Motion Graphics']);
    Sample::factory()->for($service)->create(['title' => 'Title sequence']);

    $this->get('/studio/samples')
        ->assertOk()
        ->assertSee('Motion Graphics')
        ->assertSee('Title sequence');
});

it('stores an uploaded film on the films disk and records its path', function () {
    Storage::fake('films');
    config(['filesystems.films' => 'films']);

    $sample = Sample::factory()->for(Service::factory())->create(['video_path' => null]);

    Livewire::test('admin.samples')
        ->set("films.{$sample->id}", UploadedFile::fake()->create('reel.mp4', 512, 'video/mp4'))
        ->assertHasNoErrors();

    $sample->refresh();

    expect($sample->video_path)->not->toBeNull();
    Storage::disk('films')->assertExists($sample->video_path);
});

it('rejects a file that is not a video', function () {
    Storage::fake('films');
    config(['filesystems.films' => 'films']);

    $sample = Sample::factory()->for(Service::factory())->create(['video_path' => null]);

    Livewire::test('admin.samples')
        ->set("films.{$sample->id}", UploadedFile::fake()->create('notes.pdf', 16, 'application/pdf'))
        ->assertHasErrors("films.{$sample->id}");

    expect($sample->refresh()->video_path)->toBeNull();
});

it('deletes the previous film when a replacement is uploaded', function () {
    Storage::fake('films');
    config(['filesystems.films' => 'films']);

    $sample = Sample::factory()->for(Service::factory())->create(['video_path' => null]);

    Livewire::test('admin.samples')
        ->set("films.{$sample->id}", UploadedFile::fake()->create('first.mp4', 128, 'video/mp4'));

    $first = $sample->refresh()->video_path;

    Livewire::test('admin.samples')
        ->set("films.{$sample->id}", UploadedFile::fake()->create('second.mp4', 128, 'video/mp4'));

    $second = $sample->refresh()->video_path;

    expect($second)->not->toBe($first);
    Storage::disk('films')->assertMissing($first);
    Storage::disk('films')->assertExists($second);
});

it('removes a film and deletes it from storage', function () {
    Storage::fake('films');
    config(['filesystems.films' => 'films']);

    $sample = Sample::factory()->for(Service::factory())->create(['video_path' => null]);

    Livewire::test('admin.samples')
        ->set("films.{$sample->id}", UploadedFile::fake()->create('reel.mp4', 128, 'video/mp4'));

    $path = $sample->refresh()->video_path;

    Livewire::test('admin.samples')->call('removeFilm', $sample->id);

    expect($sample->refresh()->video_path)->toBeNull();
    Storage::disk('films')->assertMissing($path);
});

it('saves a pasted link', function () {
    $sample = Sample::factory()->for(Service::factory())->create(['video_url' => null]);

    Livewire::test('admin.samples')
        ->set("urls.{$sample->id}", 'https://youtu.be/dQw4w9WgXcQ')
        ->call('saveUrl', $sample->id)
        ->assertHasNoErrors();

    expect($sample->refresh()->video_url)->toBe('https://youtu.be/dQw4w9WgXcQ');
});

it('rejects a link that is not a web address', function () {
    $sample = Sample::factory()->for(Service::factory())->create(['video_url' => null]);

    Livewire::test('admin.samples')
        ->set("urls.{$sample->id}", 'not a url')
        ->call('saveUrl', $sample->id)
        ->assertHasErrors("urls.{$sample->id}");

    expect($sample->refresh()->video_url)->toBeNull();
});

it('clears a link when the field is emptied', function () {
    $sample = Sample::factory()->for(Service::factory())
        ->create(['video_url' => 'https://youtu.be/dQw4w9WgXcQ']);

    Livewire::test('admin.samples')
        ->set("urls.{$sample->id}", '')
        ->call('saveUrl', $sample->id)
        ->assertHasNoErrors();

    expect($sample->refresh()->video_url)->toBeNull();
});

it('reports a storage failure instead of claiming the upload worked', function () {
    // The films disk sets throw => false, so a failed write returns false
    // rather than raising. Root a real local disk beneath an existing *file*
    // so the driver genuinely cannot create the directory, and confirm the
    // component reports it instead of recording a path.
    $blocker = storage_path('framework/testing/blocked-disk');
    @mkdir(dirname($blocker), 0777, true);
    file_put_contents($blocker, 'not a directory');

    config([
        'filesystems.disks.blocked' => [
            'driver' => 'local',
            'root' => $blocker.DIRECTORY_SEPARATOR.'films',
            'throw' => false,
        ],
        'filesystems.films' => 'blocked',
    ]);

    $sample = Sample::factory()->for(Service::factory())->create(['video_path' => null]);

    Livewire::test('admin.samples')
        ->set("films.{$sample->id}", UploadedFile::fake()->create('reel.mp4', 128, 'video/mp4'))
        ->assertHasErrors("films.{$sample->id}");

    expect($sample->refresh()->video_path)->toBeNull();
});
