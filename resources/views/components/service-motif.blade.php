@props(['service'])

{{--
    A motif per service, built from the discipline itself rather than from
    decoration: branching nodes for Articulate, onion-skinned frames for 2D,
    a wireframe solid for 3D, a resolving sample grid for AI, sequenced panels
    for storyboards, kinetic bars for motion graphics.

    All geometry and CSS keyframes - no asset, no dependency - and each inherits
    the service's own two stops so no two sections share a palette.
--}}
<div
    class="motif pointer-events-none absolute inset-0 -z-10 overflow-hidden"
    style="--from: {{ $service->accent_from }}; --to: {{ $service->accent_to }}"
    aria-hidden="true"
>
    {{-- Colour splash: a wash of the service's own gradient that blooms as the
         section comes into view and swells again on hover. --}}
    <span class="motif-splash absolute -right-24 -top-24 size-[34rem] rounded-full blur-3xl"></span>

    <svg class="motif-art absolute right-4 top-1/2 h-56 w-56 -translate-y-1/2 opacity-[0.16] sm:right-10 sm:h-72 sm:w-72"
         viewBox="0 0 200 200" fill="none" stroke="currentColor" stroke-width="1.5"
         stroke-linecap="round" stroke-linejoin="round">
        @switch ($service->slug)
            @case('articulate')
                {{-- Branching paths, the shape of a course that adapts --}}
                <circle cx="100" cy="100" r="74" class="motif-ring" stroke-dasharray="6 10" />
                <path class="motif-branch" d="M40 100h34m0 0 26-30h60m-86 30 26 30h60" />
                <circle class="motif-node motif-node-1" cx="40" cy="100" r="7" fill="currentColor" stroke="none" />
                <circle class="motif-node motif-node-2" cx="160" cy="70" r="7" fill="currentColor" stroke="none" />
                <circle class="motif-node motif-node-3" cx="160" cy="130" r="7" fill="currentColor" stroke="none" />
                @break

            @case('2d-animation')
                {{-- Onion-skinned frames, each a step behind the last --}}
                <rect class="motif-frame motif-frame-3" x="34" y="46" width="108" height="108" rx="8" />
                <rect class="motif-frame motif-frame-2" x="48" y="46" width="108" height="108" rx="8" />
                <rect class="motif-frame motif-frame-1" x="62" y="46" width="108" height="108" rx="8" />
                <path class="motif-stroke" d="M74 128c14-46 42-58 70-24" stroke-width="2.5" />
                @break

            @case('3d-animation')
                {{-- A wireframe solid turning on its axis --}}
                <g class="motif-spin" style="transform-origin: 100px 100px">
                    <path d="M100 34 46 66v68l54 32 54-32V66l-54-32Z" />
                    <path d="M100 34v68m0 0 54-36m-54 36-54-36m54 36v64" />
                    <path d="M46 66v68l54 32 54-32V66" opacity="0.5" />
                </g>
                @break

            @case('ai-videos')
                {{-- A sample grid resolving under a scan --}}
                <g class="motif-grid">
                    @for ($r = 0; $r < 6; $r++)
                        @for ($c = 0; $c < 6; $c++)
                            <rect x="{{ 40 + $c * 21 }}" y="{{ 40 + $r * 21 }}" width="13" height="13" rx="3"
                                  fill="currentColor" stroke="none"
                                  style="--i: {{ $r * 6 + $c }}" class="motif-cell" />
                        @endfor
                    @endfor
                </g>
                <path class="motif-scan" d="M32 40h136" stroke-width="2.5" />
                @break

            @case('storyboarding')
                {{-- Panels filling in, one beat at a time --}}
                <g class="motif-panels">
                    <rect class="motif-panel motif-panel-1" x="26" y="58" width="64" height="46" rx="5" />
                    <rect class="motif-panel motif-panel-2" x="106" y="58" width="64" height="46" rx="5" />
                    <rect class="motif-panel motif-panel-3" x="26" y="118" width="64" height="46" rx="5" />
                    <rect class="motif-panel motif-panel-4" x="106" y="118" width="64" height="46" rx="5" />
                </g>
                <path class="motif-sketch" d="M36 94c10-20 22-26 34-12M116 94c12-16 24-14 34 4M36 154c12-14 22-16 34-6M116 154c10-18 24-18 34 0" opacity="0.7" />
                @break

            @default
                {{-- Kinetic bars: type and data set moving --}}
                <g class="motif-bars">
                    @foreach ([[46, 0], [72, 1], [98, 2], [124, 3], [150, 4]] as [$x, $i])
                        <rect class="motif-bar" x="{{ $x }}" y="70" width="14" height="60" rx="7"
                              fill="currentColor" stroke="none" style="--i: {{ $i }}" />
                    @endforeach
                </g>
                <circle class="motif-orbit" cx="100" cy="100" r="82" stroke-dasharray="3 12" />
        @endswitch
    </svg>
</div>
