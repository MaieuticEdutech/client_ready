<?php

use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('films');
    config(['filesystems.films' => 'films']);

    $this->folder = storage_path('framework/testing/films-in');
    File::ensureDirectoryExists($this->folder);
    File::cleanDirectory($this->folder);
});

afterEach(function () {
    File::deleteDirectory($this->folder);
});

function dropFilm(string $folder, string $name, int $bytes = 1024): void
{
    file_put_contents($folder.DIRECTORY_SEPARATOR.$name, str_repeat('0', $bytes));
}

it('uploads every video in the folder', function () {
    dropFilm($this->folder, 'first.mp4');
    dropFilm($this->folder, 'second.webm');

    $this->artisan('films:push', ['directory' => $this->folder])
        ->assertSuccessful();

    Storage::disk('films')->assertExists('films/first.mp4');
    Storage::disk('films')->assertExists('films/second.webm');
});

it('normalises spaces and capitals in the stored name', function () {
    dropFilm($this->folder, 'My Test Film.MP4');

    $this->artisan('films:push', ['directory' => $this->folder])
        ->assertSuccessful();

    Storage::disk('films')->assertExists('films/my-test-film.mp4');
});

it('honours a custom prefix', function () {
    dropFilm($this->folder, 'clip.mp4');

    $this->artisan('films:push', ['directory' => $this->folder, '--prefix' => 'reels'])
        ->assertSuccessful();

    Storage::disk('films')->assertExists('reels/clip.mp4');
});

it('warns about formats a browser cannot play', function () {
    dropFilm($this->folder, 'raw.mov');

    $this->artisan('films:push', ['directory' => $this->folder])
        ->expectsOutputToContain('most browsers cannot play it')
        ->assertSuccessful();
});

it('uploads nothing on a dry run', function () {
    dropFilm($this->folder, 'clip.mp4');

    $this->artisan('films:push', ['directory' => $this->folder, '--dry-run' => true])
        ->assertSuccessful();

    expect(Storage::disk('films')->allFiles())->toBeEmpty();
});

it('ignores files that are not videos', function () {
    dropFilm($this->folder, 'notes.pdf');
    dropFilm($this->folder, 'clip.mp4');

    $this->artisan('films:push', ['directory' => $this->folder])
        ->assertSuccessful();

    expect(Storage::disk('films')->allFiles())->toBe(['films/clip.mp4']);
});

it('fails loudly when the folder does not exist', function () {
    $this->artisan('films:push', ['directory' => $this->folder.'/nope'])
        ->assertFailed();
});
