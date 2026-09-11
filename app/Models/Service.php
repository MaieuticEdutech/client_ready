<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'tagline', 'description',
        'accent_from', 'accent_to', 'sort_order', 'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $service) {
            if (blank($service->slug)) {
                $service->slug = Str::slug($service->name);
            }
        });
    }

    public function samples(): HasMany
    {
        return $this->hasMany(Sample::class)->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Samples grouped under their sub-heading, in the order they first appear.
     * Samples without a category share a single group keyed by an empty string.
     */
    public function sampleGroups(): Collection
    {
        return $this->samples->groupBy(fn (Sample $sample) => $sample->category ?? '');
    }

    /**
     * Whether this service splits its samples under sub-headings.
     */
    public function hasCategories(): bool
    {
        return $this->samples->contains(fn (Sample $sample) => filled($sample->category));
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeInOrder(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * The wash behind a section heading, built from the service's own two stops.
     */
    public function gradient(): string
    {
        return "linear-gradient(135deg, {$this->accent_from}, {$this->accent_to})";
    }
}
