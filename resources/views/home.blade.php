<x-layouts::app :title="config('app.name').' — What we make'">
    {{-- Hero --}}
    <header class="relative overflow-hidden border-b border-ink-900/10">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0 -z-10">
            <div class="hero-drift-one absolute -left-32 -top-40 size-[38rem] rounded-full bg-brand-mint/12 blur-3xl"></div>
            <div class="hero-drift-two absolute -right-24 -top-10 size-[32rem] rounded-full bg-brand-peach/15 blur-3xl"></div>
        </div>

        {{-- Top bar: the company mark sits top left --}}
        <div class="mx-auto flex max-w-7xl items-center justify-start px-5 pt-6 sm:px-8 sm:pt-8">
            <a href="{{ url('/') }}" class="inline-block transition hover:opacity-80" aria-label="Maieutic Edutech home">
                <img src="{{ asset('images/maieutic-logo.webp') }}" alt="Maieutic Edutech" class="h-11 w-auto sm:h-14" width="1600" height="525" fetchpriority="high">
            </a>
        </div>

        <div class="mx-auto max-w-7xl px-5 pb-20 pt-12 sm:px-8 sm:pb-28 sm:pt-14 lg:grid lg:grid-cols-[1.05fr_0.95fr] lg:items-center lg:gap-14 xl:gap-20">
            <div>
                <p class="reveal text-xs font-semibold uppercase tracking-[0.3em] text-brand-teal">Maieutic &mdash; The art of drawing out knowledge</p>

                <h1 class="reveal mt-6 text-balance text-5xl font-bold leading-[1.02] tracking-tight text-ink-900 sm:text-6xl xl:text-7xl" style="--reveal-delay: 80ms">
                    Watch ideas
                    <span class="bg-gradient-to-r from-brand-mint via-brand-teal to-brand-deep bg-clip-text text-transparent">come to life.</span>
                </h1>

                <p class="reveal mt-8 max-w-2xl text-lg leading-relaxed text-ink-900/60" style="--reveal-delay: 160ms">
                    A curated look at how we turn complex training into content people actually remember &mdash;
                    interactive courses, AI presenters, and motion design, crafted end to end for education and enterprise.
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

            {{-- About panel: the company at a glance, its numbers climbing into place on arrival --}}
            <aside
                class="about-panel reveal relative mt-14 overflow-hidden rounded-[2rem] p-7 text-white shadow-2xl shadow-brand-deep/20 sm:p-10 lg:mt-0"
                style="--reveal-delay: 320ms"
                aria-labelledby="about-title"
            >
                <div aria-hidden="true" class="pointer-events-none absolute inset-0">
                    <span class="absolute -right-20 -top-24 size-72 rounded-full bg-brand-mint/25 blur-3xl"></span>
                    <span class="absolute -bottom-24 -left-16 size-64 rounded-full bg-brand-coral/20 blur-3xl"></span>
                    <svg class="about-orbit absolute -right-14 -top-14 size-64 text-white/15" viewBox="0 0 200 200" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="100" cy="100" r="82" stroke-dasharray="3 12" />
                        <circle cx="100" cy="100" r="56" stroke-dasharray="2 8" opacity="0.6" />
                    </svg>
                </div>

                <div class="relative">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-brand-mint">Who we are</p>
                    <h2 id="about-title" class="mt-3 font-serif text-3xl font-semibold leading-tight sm:text-4xl">Maieutic Edutech</h2>
                    <p class="mt-4 max-w-md text-sm leading-relaxed text-white/75 sm:text-base">
                        Founded in 2018 and headquartered in Bengaluru, Maieutic is a leading eLearning solutions company
                        committed to transforming digital learning experiences &mdash; bridging the gap between learning
                        and real-world application.
                    </p>

                    {{-- The numbers are built like a sample card's poster panel: one gradient
                         tile per figure, with the cursor wash, gloss and shine, and the figure
                         and its label sitting where a film's caption would. --}}
                    <dl class="mt-8 grid grid-cols-1 gap-3 sm:grid-cols-3 sm:gap-4">
                        @foreach ([
                            ['2018', '', 'Founded in Bengaluru', '#15D9A1', '#00615C'],
                            ['50', '+', 'Clients', '#F2AA84', '#A31009'],
                            ['100', '%', 'In-House Multidisciplinary Team', '#F8847E', '#800D07'],
                        ] as $i => [$value, $suffix, $label, $from, $to])
                            <div
                                class="sample-card reveal group relative isolate flex aspect-video flex-col justify-end overflow-hidden rounded-2xl bg-ink-900 p-4 ring-1 ring-white/15 transition duration-500 ease-out hover:-translate-y-1.5 hover:ring-white/30 sm:aspect-[4/3]"
                                style="--from: {{ $from }}; --to: {{ $to }}; --reveal-delay: {{ 420 + $i * 90 }}ms"
                                x-data
                                x-on:pointermove="
                                    const r = $el.getBoundingClientRect();
                                    $el.style.setProperty('--x', (($event.clientX - r.left) / r.width * 100) + '%');
                                    $el.style.setProperty('--y', (($event.clientY - r.top) / r.height * 100) + '%');
                                "
                            >
                                <span aria-hidden="true" class="absolute inset-0 -z-10 opacity-90" style="background: linear-gradient(135deg, {{ $from }}, {{ $to }})"></span>
                                <span aria-hidden="true" class="sample-splash absolute inset-0 -z-10 mix-blend-screen"></span>
                                <span aria-hidden="true" class="absolute inset-0 -z-10 bg-gradient-to-t from-black/80 via-black/25 to-transparent"></span>
                                <span aria-hidden="true" class="sample-gloss pointer-events-none absolute inset-0 -z-10"></span>
                                <span aria-hidden="true" class="sample-shine pointer-events-none absolute inset-0 -z-10"></span>

                                <dt class="order-2 mt-2 text-[10px] font-medium uppercase leading-snug tracking-[0.14em] text-white/60">{{ $label }}</dt>
                                <dd class="order-1 bg-gradient-to-r from-brand-mint to-brand-aqua bg-clip-text font-serif text-4xl font-semibold tabular-nums leading-none text-transparent">
                                    <span data-count="{{ $value }}">{{ $value }}</span>{{ $suffix }}
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
            </aside>
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

                <div class="relative mx-auto max-w-7xl px-5 sm:px-8">
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

                    {{-- Samples, split under sub-headings when the service has them --}}
                    @foreach ($service->sampleGroups() as $category => $samples)
                        @if ($service->hasCategories())
                            <h3 class="reveal mt-14 flex items-center gap-4 text-2xl font-bold tracking-tight text-ink-900 sm:text-3xl">
                                <span class="h-px w-6 shrink-0" style="background: {{ $service->gradient() }}"></span>
                                {{ $category !== '' ? $category : 'More' }}
                            </h3>
                        @endif

                        <div class="{{ $service->hasCategories() ? 'mt-6' : 'mt-12' }} grid gap-6 sm:grid-cols-2 lg:grid-cols-3 lg:gap-8">
                            @foreach ($samples as $sample)
                                <x-sample-card :sample="$sample" :service="$service" />
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach
    </main>

    <x-site-footer />
</x-layouts::app>
