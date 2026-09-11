<div>
    <h1 class="text-2xl font-bold text-white">Samples</h1>
    <p class="mt-1 text-sm text-white/40">
        Attach a film to any sample &mdash; upload a file, or paste a YouTube or Vimeo link.
        An uploaded file always wins over a link.
    </p>

    <div class="mt-8 space-y-10">
        @foreach ($this->services as $service)
            <section>
                <div class="flex items-center gap-3">
                    <span aria-hidden="true" class="size-3 shrink-0 rounded-full" style="background: {{ $service->gradient() }}"></span>
                    <h2 class="font-semibold text-white">{{ $service->name }}</h2>
                    <span class="text-xs text-white/30">{{ $service->samples->count() }} {{ Str::plural('sample', $service->samples->count()) }}</span>
                </div>

                <div class="mt-4 space-y-3">
                    @forelse ($service->samples as $sample)
                        <div wire:key="sample-{{ $sample->id }}" class="rounded-2xl border border-white/10 bg-ink-800 p-4 sm:p-5">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="font-medium text-white">{{ $sample->title }}</p>
                                    <p class="mt-0.5 text-xs text-white/30">
                                        @if ($sample->category)
                                            {{ $sample->category }} &middot;
                                        @endif
                                        @if ($sample->isUploaded())
                                            Uploaded film
                                        @elseif ($sample->embedUrl())
                                            Linked video
                                        @elseif ($sample->video_url)
                                            Link saved, but not a YouTube or Vimeo address
                                        @else
                                            Footage coming soon
                                        @endif
                                    </p>
                                </div>

                                @if ($sample->isUploaded())
                                    <span class="rounded-full bg-brand-teal/20 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wider text-brand-mint">Film</span>
                                @elseif ($sample->embedUrl())
                                    <span class="rounded-full bg-white/10 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wider text-white/60">Link</span>
                                @else
                                    <span class="rounded-full bg-white/5 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wider text-white/30">Empty</span>
                                @endif
                            </div>

                            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                                {{-- Upload --}}
                                <div wire:key="film-{{ $sample->id }}">
                                    <label for="film-{{ $sample->id }}" class="text-xs font-medium uppercase tracking-wider text-white/40">
                                        Upload a film
                                    </label>

                                    <input
                                        id="film-{{ $sample->id }}"
                                        type="file"
                                        accept="video/mp4,video/quicktime,video/webm"
                                        wire:model="films.{{ $sample->id }}"
                                        class="mt-1.5 block w-full text-sm text-white/60 file:mr-3 file:rounded-lg file:border-0 file:bg-white/10 file:px-3 file:py-1.5 file:text-sm file:text-white hover:file:bg-white/20"
                                    >

                                    <p wire:loading wire:target="films.{{ $sample->id }}" class="mt-1.5 text-xs text-brand-mint">
                                        Uploading&hellip;
                                    </p>

                                    @error('films.'.$sample->id)
                                        <p class="mt-1.5 text-xs text-brand-coral">{{ $message }}</p>
                                    @enderror

                                    @if ($sample->isUploaded())
                                        <button
                                            type="button"
                                            wire:click="removeFilm({{ $sample->id }})"
                                            wire:confirm="Remove this film? The file is deleted from storage."
                                            class="mt-2 text-xs text-white/40 transition hover:text-brand-coral"
                                        >
                                            Remove film
                                        </button>
                                    @endif
                                </div>

                                {{-- Link --}}
                                <div wire:key="url-{{ $sample->id }}">
                                    <label for="url-{{ $sample->id }}" class="text-xs font-medium uppercase tracking-wider text-white/40">
                                        Or paste a link
                                    </label>

                                    <div class="mt-1.5 flex gap-2">
                                        <input
                                            id="url-{{ $sample->id }}"
                                            type="url"
                                            inputmode="url"
                                            placeholder="https://youtu.be/&hellip;"
                                            wire:model="urls.{{ $sample->id }}"
                                            wire:keydown.enter="saveUrl({{ $sample->id }})"
                                            class="min-w-0 flex-1 rounded-lg border border-white/10 bg-ink-700 px-3 py-1.5 text-sm text-white placeholder-white/25 transition focus:border-brand-mint/60 focus:outline-none"
                                        >

                                        <button
                                            type="button"
                                            wire:click="saveUrl({{ $sample->id }})"
                                            class="shrink-0 rounded-lg bg-white/10 px-3 py-1.5 text-sm text-white transition hover:bg-white/20"
                                        >
                                            Save
                                        </button>
                                    </div>

                                    @error('urls.'.$sample->id)
                                        <p class="mt-1.5 text-xs text-brand-coral">{{ $message }}</p>
                                    @enderror

                                    @if ($sample->isUploaded() && $sample->video_url)
                                        <p class="mt-1.5 text-xs text-white/30">
                                            The uploaded film is showing on the site; this link is kept but unused.
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="rounded-2xl border border-dashed border-white/10 p-6 text-center text-sm text-white/30">
                            No samples for this service yet.
                        </p>
                    @endforelse
                </div>
            </section>
        @endforeach
    </div>
</div>
