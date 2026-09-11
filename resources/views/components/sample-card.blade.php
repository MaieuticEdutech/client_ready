@props(['sample', 'service'])

{{--
    One sample. Plays its film on hover when there is one, shows an embed link
    when the source is a pasted URL, and states plainly that footage is pending
    rather than pretending otherwise.
--}}
{{-- The splash follows the cursor: pointer position is written straight to two
     custom properties, so the wash tracks the hand without a re-render. --}}
<figure
    class="sample-card reveal group relative overflow-hidden rounded-2xl bg-ink-900 ring-1 ring-ink-900/10 transition duration-500 ease-out hover:-translate-y-1.5 hover:ring-ink-900/20"
    style="--from: {{ $service->accent_from }}; --to: {{ $service->accent_to }}"
    x-data
    x-on:pointermove="
        const r = $el.getBoundingClientRect();
        $el.style.setProperty('--x', (($event.clientX - r.left) / r.width * 100) + '%');
        $el.style.setProperty('--y', (($event.clientY - r.top) / r.height * 100) + '%');
    "
    @if ($sample->isUploaded())
        x-on:pointerenter="if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) $refs.film?.play()"
        x-on:pointerleave="if ($refs.film) { $refs.film.pause(); $refs.film.currentTime = 0 }"
    @endif
>
    <div class="relative aspect-video w-full overflow-hidden">
        <span aria-hidden="true" class="absolute inset-0 opacity-90" style="background: {{ $service->gradient() }}"></span>
        <span aria-hidden="true" class="sample-splash absolute inset-0 mix-blend-screen"></span>
        <span aria-hidden="true" class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-transparent"></span>
        <span aria-hidden="true" class="sample-gloss pointer-events-none absolute inset-0"></span>
        <span aria-hidden="true" class="sample-shine pointer-events-none absolute inset-0"></span>

        @if ($sample->isUploaded())
            <video
                x-ref="film"
                muted loop playsinline preload="none"
                aria-hidden="true" tabindex="-1"
                @if ($sample->thumbnailUrl()) poster="{{ $sample->thumbnailUrl() }}" @endif
                class="absolute inset-0 size-full object-cover opacity-0 transition-opacity duration-500 group-hover:opacity-100"
            >
                <source src="{{ $sample->source() }}">
            </video>
        @elseif ($embed = $sample->embedUrl())
            <iframe
                src="{{ $embed }}"
                title="{{ $sample->title }}"
                loading="lazy"
                allowfullscreen
                class="absolute inset-0 size-full border-0"
            ></iframe>
        @elseif ($sample->thumbnailUrl())
            <img src="{{ $sample->thumbnailUrl() }}" alt="{{ $sample->title }}" loading="lazy" decoding="async" class="absolute inset-0 size-full object-cover">
        @endif

        @unless ($sample->embedUrl())
            <span class="absolute inset-x-0 bottom-0 p-5">
                <span class="flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.2em] text-white/70">
                    @if ($sample->isPending())
                        <svg class="size-3" viewBox="0 0 12 12" fill="currentColor" aria-hidden="true"><circle cx="6" cy="6" r="5" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>
                        Footage coming soon
                    @else
                        <svg class="size-3" viewBox="0 0 12 12" fill="currentColor" aria-hidden="true"><path d="M3 1.5v9l7.5-4.5L3 1.5Z"/></svg>
                        Play
                    @endif
                </span>
            </span>
        @endunless
    </div>

    <figcaption class="flex items-start justify-between gap-4 bg-white p-5">
        <div class="min-w-0">
            <p class="truncate font-semibold text-ink-900 transition-colors duration-300 group-hover:text-brand-teal">{{ $sample->title }}</p>
            @if ($sample->client)
                <p class="mt-0.5 truncate text-sm text-ink-900/50">{{ $sample->client }}</p>
            @endif
        </div>

        @if ($sample->duration_label)
            <span class="shrink-0 text-xs tabular-nums text-ink-900/40">{{ $sample->duration_label }}</span>
        @endif
    </figcaption>
</figure>
