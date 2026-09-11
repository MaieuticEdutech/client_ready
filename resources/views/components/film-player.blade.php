{{--
    The player. One dialog for the whole page: a card puts its film here on
    click, sized to the viewport, with sound and the film's own controls.
    Driven by resources/js/player.js.
--}}
<dialog id="player" class="player" aria-labelledby="player-title">
    <div class="player-frame relative aspect-video w-full overflow-hidden rounded-2xl bg-black" data-player-frame></div>

    <div class="mt-4 flex items-start justify-between gap-6 px-1">
        <div class="min-w-0">
            <p id="player-title" class="truncate text-lg font-semibold text-white" data-player-title></p>
            <p class="mt-0.5 truncate text-sm text-white/55" data-player-meta></p>
        </div>

        <button
            type="button"
            data-player-close
            class="flex shrink-0 items-center gap-2 rounded-full border border-white/15 px-4 py-2 text-sm font-medium text-white/80 transition hover:border-white/40 hover:text-white"
        >
            Close
            <svg viewBox="0 0 24 24" class="size-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18" /></svg>
        </button>
    </div>
</dialog>
