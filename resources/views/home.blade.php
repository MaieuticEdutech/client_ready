<x-layouts::app :title="config('app.name').' — What we make'">
    {{-- Hero --}}
    <header class="relative overflow-hidden border-b border-ink-900/10">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0 -z-10">
            <div class="hero-drift-one absolute -left-32 -top-40 size-[38rem] rounded-full bg-brand-mint/12 blur-3xl"></div>
            <div class="hero-drift-two absolute -right-24 -top-10 size-[32rem] rounded-full bg-brand-peach/15 blur-3xl"></div>
        </div>

        <div class="mx-auto max-w-6xl px-5 py-24 sm:px-8 sm:py-32">
            <p class="reveal text-xs font-semibold uppercase tracking-[0.3em] text-brand-teal">Maieutic</p>

            <h1 class="reveal mt-6 max-w-4xl text-balance text-5xl font-bold leading-[1.02] tracking-tight text-ink-900 sm:text-7xl" style="--reveal-delay: 80ms">
                Six ways we make
                <span class="bg-gradient-to-r from-brand-mint via-brand-teal to-brand-deep bg-clip-text text-transparent">learning move.</span>
            </h1>

            <p class="reveal mt-8 max-w-2xl text-lg leading-relaxed text-ink-900/60" style="--reveal-delay: 160ms">
                Interactive courses, animation, synthetic presenters and motion design &mdash;
                produced end to end for education and enterprise.
            </p>

            {{-- Jump list doubles as the table of contents --}}
            <nav class="reveal mt-12 flex flex-wrap gap-2" aria-label="Our work" style="--reveal-delay: 240ms">
                @foreach ($services as $service)
                    <a
                        href="#{{ $service->slug }}"
                        class="rounded-full border border-ink-900/12 bg-white px-4 py-2 text-sm font-medium text-ink-900/65 transition hover:border-brand-teal/50 hover:text-brand-teal"
                    >{{ $service->name }}</a>
                @endforeach
            </nav>
        </div>
    </header>

    <main>
        @foreach ($services as $index => $service)
            <section
                id="{{ $service->slug }}"
                class="relative isolate scroll-mt-8 overflow-hidden border-b border-ink-900/10 py-20 sm:py-28 {{ $index % 2 ? 'bg-ink-900/[0.02]' : '' }}"
                aria-labelledby="{{ $service->slug }}-title"
            >
                <x-service-motif :service="$service" />

                <div class="relative mx-auto max-w-6xl px-5 sm:px-8">
                    <div class="reveal grid gap-8 lg:grid-cols-[1fr_1.15fr] lg:items-end lg:gap-16">
                        <div>
                            <span class="flex items-center gap-3 text-[11px] font-semibold uppercase tracking-[0.28em] text-ink-900/35">
                                <span class="tabular-nums">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="h-px w-8" style="background: {{ $service->gradient() }}"></span>
                            </span>

                            <h2 id="{{ $service->slug }}-title" class="mt-4 text-balance text-4xl font-bold leading-[1.05] tracking-tight text-ink-900 sm:text-5xl">
                                {{ $service->name }}
                            </h2>

                            @if ($service->tagline)
                                <p class="mt-3 bg-clip-text text-lg font-semibold text-transparent" style="background-image: {{ $service->gradient() }}">
                                    {{ $service->tagline }}
                                </p>
                            @endif
                        </div>

                        @if ($service->description)
                            <p class="max-w-xl text-base leading-relaxed text-ink-900/60 sm:text-lg">
                                {{ $service->description }}
                            </p>
                        @endif
                    </div>

                    {{-- Samples --}}
                    <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3 lg:gap-8">
                        @foreach ($service->samples as $sample)
                            <x-sample-card :sample="$sample" :service="$service" />
                        @endforeach
                    </div>
                </div>
            </section>
        @endforeach
    </main>

    <footer class="py-14">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-5 text-sm text-ink-900/40 sm:px-8">
            <span>&copy; {{ now()->year }} {{ config('app.name') }}. All rights reserved.</span>
            <a href="mailto:{{ config('mail.from.address') }}" class="transition hover:text-brand-teal">Start a project</a>
        </div>
    </footer>
</x-layouts::app>
