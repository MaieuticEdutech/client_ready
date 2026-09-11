@php
    $site = 'https://maieuticedutech.com';

    $columns = [
        'Solutions' => [
            ['All Solutions', $site.'/solutions'],
            ['Content Design & Development', $site.'/solutions/content-design-development'],
            ['Marketing, Digital Products & Network', $site.'/solutions/marketing-digital-products'],
            ['Academic Delivery', $site.'/solutions/academic-delivery-student-success'],
            ['LMS Deployment & Management', $site.'/solutions/lms-deployment-management'],
            ['Interactive Models & Articulate', $site.'/solutions/interactive-models-articulate'],
            ['Video Based Learning', $site.'/solutions/video-based-learning'],
            ['2D / 3D / Motion Graphics', $site.'/solutions/2d-3d-motion-graphics'],
        ],
        'Company' => [
            ['Who We Are', $site.'/about-us'],
            ['Our Vision', $site.'/about-us'],
            ['Our Mission', $site.'/about-us'],
            ['Our Impact', $site.'/about-us'],
            ['Our Clients', $site.'/clients'],
            ['Careers', $site.'/careers'],
            ['Gallery', $site.'/gallery'],
        ],
        'Resources' => [
            ['Blogs & Insights', $site.'/resources/blogs-insights'],
            ['Case Studies', $site.'/resources/case-studies'],
            ['FAQs', $site.'/faqs'],
        ],
        'Contact Us' => [
            ['Send Us a Message', $site.'/contact'],
            ['Email Us', 'mailto:careers@maieuticedutech.com'],
            ['Call Us', 'tel:+919663727955'],
            ['Our Location', $site.'/contact'],
        ],
    ];

    $socials = [
        ['LinkedIn', 'https://www.linkedin.com/company/maieuticedutech', 'M4.98 3.5C4.98 4.88 3.87 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1s2.48 1.12 2.48 2.5ZM.22 8.22h4.56V23H.22V8.22Zm7.34 0h4.37v2.02h.06c.61-1.15 2.1-2.37 4.32-2.37 4.62 0 5.47 3.04 5.47 6.99V23h-4.55v-7.2c0-1.72-.03-3.93-2.39-3.93-2.4 0-2.76 1.87-2.76 3.8V23H7.56V8.22Z'],
        ['YouTube', 'https://www.youtube.com/@maieuticedutech', 'M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.6 12 3.6 12 3.6s-7.5 0-9.4.5A3 3 0 0 0 .5 6.2C0 8.1 0 12 0 12s0 3.9.5 5.8a3 3 0 0 0 2.1 2.1c1.9.5 9.4.5 9.4.5s7.5 0 9.4-.5a3 3 0 0 0 2.1-2.1c.5-1.9.5-5.8.5-5.8s0-3.9-.5-5.8ZM9.6 15.6V8.4l6.2 3.6-6.2 3.6Z'],
        ['Instagram', 'https://www.instagram.com/maieuticedutech', 'M12 2.2c3.2 0 3.6 0 4.8.1 1.2.1 1.8.2 2.2.4.6.2 1 .5 1.4.9.4.4.7.8.9 1.4.2.4.4 1 .4 2.2.1 1.3.1 1.6.1 4.8s0 3.6-.1 4.8c-.1 1.2-.2 1.8-.4 2.2-.2.6-.5 1-.9 1.4-.4.4-.8.7-1.4.9-.4.2-1 .4-2.2.4-1.3.1-1.6.1-4.8.1s-3.6 0-4.8-.1c-1.2-.1-1.8-.2-2.2-.4-.6-.2-1-.5-1.4-.9-.4-.4-.7-.8-.9-1.4-.2-.4-.4-1-.4-2.2-.1-1.3-.1-1.6-.1-4.8s0-3.6.1-4.8c.1-1.2.2-1.8.4-2.2.2-.6.5-1 .9-1.4.4-.4.8-.7 1.4-.9.4-.2 1-.4 2.2-.4 1.2-.1 1.6-.1 4.8-.1ZM12 0C8.7 0 8.3 0 7.1.1 5.8.1 4.9.3 4.1.6c-.8.3-1.5.7-2.2 1.4C1.3 2.7.9 3.4.6 4.2.3 5 .1 5.8.1 7.1 0 8.3 0 8.7 0 12s0 3.7.1 4.9c.1 1.3.3 2.1.6 2.9.3.8.7 1.5 1.4 2.2.7.7 1.4 1.1 2.2 1.4.8.3 1.6.5 2.9.6 1.2.1 1.6.1 4.9.1s3.7 0 4.9-.1c1.3-.1 2.1-.3 2.9-.6.8-.3 1.5-.7 2.2-1.4.7-.7 1.1-1.4 1.4-2.2.3-.8.5-1.6.6-2.9.1-1.2.1-1.6.1-4.9s0-3.7-.1-4.9c-.1-1.3-.3-2.1-.6-2.9-.3-.8-.7-1.5-1.4-2.2C21.3 1.3 20.6.9 19.8.6 19 .3 18.2.1 16.9.1 15.7 0 15.3 0 12 0Zm0 5.8a6.2 6.2 0 1 0 0 12.4 6.2 6.2 0 0 0 0-12.4ZM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8Zm6.4-11.8a1.4 1.4 0 1 0 0 2.9 1.4 1.4 0 0 0 0-2.9Z'],
        ['Facebook', 'https://www.facebook.com/maieuticedutech', 'M24 12.07C24 5.4 18.63 0 12 0S0 5.4 0 12.07C0 18.1 4.39 23.09 10.13 24v-8.44H7.08v-3.49h3.05V9.41c0-3.02 1.79-4.69 4.53-4.69 1.31 0 2.68.24 2.68.24v2.97h-1.51c-1.49 0-1.96.93-1.96 1.88v2.26h3.33l-.53 3.49h-2.8V24C19.61 23.09 24 18.1 24 12.07Z'],
    ];
@endphp

<footer class="bg-ink-800 text-white" aria-labelledby="footer-heading">
    <h2 id="footer-heading" class="sr-only">About {{ config('app.name') }}</h2>

    <div class="mx-auto max-w-7xl px-5 pb-12 pt-16 sm:px-8 sm:pt-20">
        <div class="grid gap-12 md:grid-cols-2 lg:grid-cols-[1.2fr_1.3fr_1fr_1fr] lg:gap-10">

            {{-- Brand, socials and address --}}
            <div class="max-w-xs">
                <a href="{{ url('/') }}" class="inline-block">
                    <img src="{{ asset('images/maieutic-logo-white.png') }}" alt="Maieutic Edutech" class="h-12 w-auto" width="2706" height="910">
                </a>

                <p class="mt-6 text-sm leading-relaxed text-white/70">
                    Empowering education through innovation and technology &mdash; from content to campus, online.
                </p>

                <ul class="mt-7 flex gap-3" aria-label="Follow Maieutic">
                    @foreach ($socials as [$label, $href, $path])
                        <li>
                            <a
                                href="{{ $href }}"
                                target="_blank"
                                rel="noopener"
                                aria-label="{{ $label }}"
                                class="flex size-10 items-center justify-center rounded-lg border border-white/15 text-white/70 transition hover:border-brand-mint/60 hover:text-brand-mint"
                            >
                                <svg viewBox="0 0 24 24" class="size-4" fill="currentColor" aria-hidden="true"><path d="{{ $path }}" /></svg>
                            </a>
                        </li>
                    @endforeach
                </ul>

                <hr class="my-8 border-white/10">

                <h3 class="text-[11px] font-bold uppercase tracking-[0.2em] text-white">Address</h3>
                <address class="mt-4 text-sm not-italic leading-relaxed text-white/70">
                    248/1, 3rd floor, Above 154 Breakfast Restaurant<br>
                    Kenchena Halli Road, 2nd Main Rd,<br>
                    Halagevadera Halli, Rajarajeshwari Nagar,<br>
                    Bengaluru, Karnataka 560098
                </address>

                <ul class="mt-6 space-y-3 text-sm">
                    <li>
                        <a href="mailto:careers@maieuticedutech.com" class="flex items-center gap-2.5 text-white/80 transition hover:text-brand-mint">
                            <svg viewBox="0 0 24 24" class="size-4 shrink-0 text-brand-mint" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2" /><path d="m3 7 9 6 9-6" /></svg>
                            careers@maieuticedutech.com
                        </a>
                    </li>
                    <li>
                        <a href="tel:+919663727955" class="flex items-center gap-2.5 text-white/80 transition hover:text-brand-mint">
                            <svg viewBox="0 0 24 24" class="size-4 shrink-0 text-brand-mint" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2Z" /></svg>
                            +91 96637 27955
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Link columns; Resources and Contact Us share the last column --}}
            @foreach (['Solutions', 'Company'] as $heading)
                <nav aria-label="{{ $heading }}">
                    <h3 class="text-[11px] font-bold uppercase tracking-[0.2em] text-white">{{ $heading }}</h3>
                    <ul class="mt-5 space-y-3 text-sm">
                        @foreach ($columns[$heading] as [$label, $href])
                            <li><a href="{{ $href }}" class="text-white/70 transition hover:text-white">{{ $label }}</a></li>
                        @endforeach
                    </ul>
                </nav>
            @endforeach

            <div class="space-y-10">
                @foreach (['Resources', 'Contact Us'] as $heading)
                    <nav aria-label="{{ $heading }}">
                        <h3 class="text-[11px] font-bold uppercase tracking-[0.2em] text-white">{{ $heading }}</h3>
                        <ul class="mt-5 space-y-3 text-sm">
                            @foreach ($columns[$heading] as [$label, $href])
                                <li><a href="{{ $href }}" class="text-white/70 transition hover:text-white">{{ $label }}</a></li>
                            @endforeach
                        </ul>
                    </nav>
                @endforeach
            </div>
        </div>

        <div class="mt-14 border-t border-white/10 pt-6 text-xs text-white/45">
            &copy; {{ now()->year }} Maieutic Edutech Pvt Ltd &mdash; All Rights Reserved
        </div>
    </div>
</footer>
