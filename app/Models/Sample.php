<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Sample extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id', 'title', 'category', 'client', 'video_url', 'video_path',
        'thumbnail_path', 'duration_label', 'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function isUploaded(): bool
    {
        return filled($this->video_path);
    }

    /**
     * An uploaded file always beats a pasted link.
     */
    public function source(): ?string
    {
        if ($this->isUploaded()) {
            return Storage::disk(config('filesystems.films'))->url($this->video_path);
        }

        return $this->video_url;
    }

    public function thumbnailUrl(): ?string
    {
        return $this->thumbnail_path
            ? Storage::disk(config('filesystems.films'))->url($this->thumbnail_path)
            : null;
    }

    /**
     * Pasted YouTube/Vimeo links need an iframe, not a <video> tag.
     */
    public function embedUrl(): ?string
    {
        if ($this->isUploaded() || blank($this->video_url)) {
            return null;
        }

        if (preg_match('#(?:youtube\.com/(?:watch\?v=|embed/)|youtu\.be/)([\w-]{11})#i', $this->video_url, $m)) {
            return "https://www.youtube.com/embed/{$m[1]}";
        }

        if (preg_match('#vimeo\.com/(?:video/)?(\d+)#i', $this->video_url, $m)) {
            return "https://player.vimeo.com/video/{$m[1]}";
        }

        return null;
    }

    public function isPending(): bool
    {
        return blank($this->source());
    }
}
