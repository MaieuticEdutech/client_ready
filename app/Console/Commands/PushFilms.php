<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SplFileInfo;
use Symfony\Component\Finder\Finder;
use Throwable;

class PushFilms extends Command
{
    protected $signature = 'films:push
                            {directory : Folder holding the video files}
                            {--prefix=films : Folder inside the bucket to upload into}
                            {--dry-run : List what would be uploaded without sending anything}';

    protected $description = 'Upload every video in a folder to the films disk and print its public URL';

    /**
     * Extensions a browser can actually play, plus the ones people commonly
     * hand over that will need converting first.
     */
    private const PLAYABLE = ['mp4', 'webm'];

    private const NEEDS_CONVERTING = ['mov', 'avi', 'wmv', 'mkv', 'flv'];

    public function handle(): int
    {
        $directory = $this->argument('directory');

        if (! is_dir($directory)) {
            $this->components->error("No such folder: {$directory}");

            return self::FAILURE;
        }

        $disk = config('filesystems.films');
        $this->components->info("Uploading to the '{$disk}' disk.");

        $files = $this->videosIn($directory);

        if ($files === []) {
            $this->components->warn('No video files found in that folder.');

            return self::SUCCESS;
        }

        $rows = [];
        $failed = 0;

        foreach ($files as $file) {
            $extension = Str::lower($file->getExtension());
            $target = $this->option('prefix').'/'.$this->safeName($file);

            if (in_array($extension, self::NEEDS_CONVERTING, true)) {
                $this->components->warn(
                    "{$file->getFilename()} is .{$extension} — most browsers cannot play it. Convert to MP4 (H.264) first."
                );
            }

            if ($this->option('dry-run')) {
                $rows[] = [$file->getFilename(), $this->humanSize($file->getSize()), 'would upload to '.$target];

                continue;
            }

            $url = $this->upload($file, $target, $disk);

            if ($url === null) {
                $failed++;
                $rows[] = [$file->getFilename(), $this->humanSize($file->getSize()), 'FAILED'];

                continue;
            }

            $rows[] = [$file->getFilename(), $this->humanSize($file->getSize()), $url];
        }

        $this->newLine();
        $this->table(['File', 'Size', $this->option('dry-run') ? 'Destination' : 'Public URL'], $rows);

        if ($failed > 0) {
            $this->components->error("{$failed} upload(s) failed.");

            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    /**
     * @return array<int, SplFileInfo>
     */
    private function videosIn(string $directory): array
    {
        $extensions = [...self::PLAYABLE, ...self::NEEDS_CONVERTING];

        $finder = Finder::create()
            ->files()
            ->in($directory)
            ->depth(0)
            ->name('/\.('.implode('|', $extensions).')$/i')
            ->sortByName();

        return iterator_to_array($finder, false);
    }

    /**
     * Upload one file, returning its public URL or null if the write failed.
     *
     * A failed write reaches us two ways: the films disk sets throw => false so
     * most failures return false, but connection errors still raise. Both mean
     * the film is not there, whatever the return value suggests.
     */
    private function upload(SplFileInfo $file, string $target, string $disk): ?string
    {
        $this->components->task("{$file->getFilename()} → {$target}", function () use ($file, $target, $disk, &$stored) {
            try {
                $stored = Storage::disk($disk)->putFileAs(
                    dirname($target),
                    $file->getPathname(),
                    basename($target),
                );
            } catch (Throwable $e) {
                report($e);
                $this->newLine();
                $this->components->error($e->getMessage());
                $stored = false;
            }

            return $stored !== false;
        });

        return $stored === false ? null : Storage::disk($disk)->url($stored);
    }

    /**
     * Spaces and capitals become percent escapes in a URL, so normalise the
     * name while keeping it recognisable.
     */
    private function safeName(SplFileInfo $file): string
    {
        $name = pathinfo($file->getFilename(), PATHINFO_FILENAME);

        return Str::slug($name).'.'.Str::lower($file->getExtension());
    }

    private function humanSize(int $bytes): string
    {
        return match (true) {
            $bytes >= 1073741824 => round($bytes / 1073741824, 1).' GB',
            $bytes >= 1048576 => round($bytes / 1048576, 1).' MB',
            default => round($bytes / 1024).' KB',
        };
    }
}
