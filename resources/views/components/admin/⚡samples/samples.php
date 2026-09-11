<?php

use App\Models\Sample;
use App\Models\Service;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    /**
     * Pending uploads, keyed by sample id. Livewire writes the temporary file
     * here as soon as one is chosen, which `updatedFilms()` then stores.
     *
     * @var array<int, TemporaryUploadedFile>
     */
    public array $films = [];

    /**
     * Pasted YouTube/Vimeo links, keyed by sample id.
     *
     * @var array<int, string>
     */
    public array $urls = [];

    public function mount(): void
    {
        $this->urls = Sample::pluck('video_url', 'id')
            ->map(fn (?string $url) => $url ?? '')
            ->all();
    }

    /**
     * @return Collection<int, Service>
     */
    #[Computed]
    public function services(): Collection
    {
        return Service::inOrder()->with('samples')->get();
    }

    /**
     * The cap Livewire itself enforces, so this component rejects an oversized
     * film with the same limit rather than a second, contradictory one.
     */
    public function maxUploadKilobytes(): int
    {
        foreach ((array) config('livewire.temporary_file_upload.rules') as $rule) {
            if (is_string($rule) && str_starts_with($rule, 'max:')) {
                return (int) substr($rule, 4);
            }
        }

        return 2097152;
    }

    /**
     * Livewire calls this the moment a file finishes uploading to the temporary
     * disk. The key is the sample id the input was bound to.
     */
    public function updatedFilms(mixed $value, string $key): void
    {
        $sample = Sample::find((int) $key);

        if (! $sample) {
            return;
        }

        $validator = Validator::make(
            ['film' => $value],
            ['film' => ['required', 'file', 'mimetypes:video/mp4,video/quicktime,video/webm', 'max:'.$this->maxUploadKilobytes()]],
            ['film.mimetypes' => 'The film must be an MP4, MOV or WebM file.'],
        );

        if ($validator->fails()) {
            $this->addError("films.{$key}", $validator->errors()->first('film'));
            unset($this->films[$key]);

            return;
        }

        $disk = config('filesystems.films');
        $previous = $sample->video_path;

        // A failed write reaches us two different ways: the films disk sets
        // throw => false so most failures return false, but directory and
        // connection errors still raise. Treat both as a failed upload —
        // without this a dead connection looks exactly like a success.
        try {
            $path = $value->store('films', $disk);
        } catch (Throwable $e) {
            report($e);
            $path = false;
        }

        if ($path === false) {
            $this->addError("films.{$key}", 'The upload could not be saved to storage. Check the film disk configuration.');
            unset($this->films[$key]);

            return;
        }

        $sample->update(['video_path' => $path]);

        if ($previous && $previous !== $path) {
            Storage::disk($disk)->delete($previous);
        }

        unset($this->films[$key], $this->services);

        $this->dispatch('toast', message: "Film attached to “{$sample->title}”.");
    }

    /**
     * Save a pasted link. An uploaded file still wins, so this warns rather
     * than silently having no effect.
     */
    public function saveUrl(int $sampleId): void
    {
        $sample = Sample::findOrFail($sampleId);
        $url = trim($this->urls[$sampleId] ?? '');

        if ($url !== '') {
            $validator = Validator::make(
                ['url' => $url],
                ['url' => ['url', 'max:2048']],
                ['url.url' => 'That does not look like a web address.'],
            );

            if ($validator->fails()) {
                $this->addError("urls.{$sampleId}", $validator->errors()->first('url'));

                return;
            }
        }

        $sample->update(['video_url' => $url ?: null]);

        unset($this->services);

        $this->dispatch('toast', message: $url === ''
            ? "Link cleared from “{$sample->title}”."
            : "Link saved for “{$sample->title}”.");
    }

    /**
     * Detach the uploaded film, leaving any pasted link to take over.
     */
    public function removeFilm(int $sampleId): void
    {
        $sample = Sample::findOrFail($sampleId);

        if ($sample->video_path) {
            Storage::disk(config('filesystems.films'))->delete($sample->video_path);
            $sample->update(['video_path' => null]);
        }

        unset($this->services);

        $this->dispatch('toast', message: "Film removed from “{$sample->title}”.");
    }
};
