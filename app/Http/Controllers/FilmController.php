<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Streams an uploaded film from a local films disk with byte-range support,
 * so a browser can start a preview instantly and seek in the player without
 * downloading the whole file. Object-storage disks serve their own URLs and
 * never come through here.
 */
class FilmController extends Controller
{
    /**
     * The most a single response carries. A browser asks for "bytes=N-" and
     * then reads at playback speed, which would pin a PHP worker for the
     * length of the film; capping the answer keeps every request short and
     * the browser simply asks for the next piece when it needs it.
     */
    private const CHUNK_BYTES = 8 * 1024 * 1024;

    public function __invoke(Request $request, string $path): BinaryFileResponse
    {
        $disk = Storage::disk(config('filesystems.films'));
        $file = 'films/'.$path;

        abort_unless($disk->exists($file), 404);

        $absolute = $disk->path($file);
        $request->headers->set('Range', $this->boundedRange($request->headers->get('Range'), filesize($absolute)));

        return response()->file($absolute, [
            'Content-Type' => 'video/mp4',
            'Cache-Control' => 'public, max-age=604800',
        ]);
    }

    /**
     * Every request becomes a range request of at most CHUNK_BYTES.
     */
    private function boundedRange(?string $range, int $size): string
    {
        $start = 0;
        $end = null;

        if ($range !== null && preg_match('/^bytes=(\d*)-(\d*)$/', $range, $m)) {
            if ($m[1] === '' && $m[2] !== '') {
                // Suffix range: the last N bytes.
                $start = max(0, $size - (int) $m[2]);
            } else {
                $start = (int) $m[1];
                $end = $m[2] === '' ? null : (int) $m[2];
            }
        }

        $cap = $start + self::CHUNK_BYTES - 1;
        $end = min($end ?? $cap, $cap, $size - 1);

        return "bytes={$start}-{$end}";
    }
}
