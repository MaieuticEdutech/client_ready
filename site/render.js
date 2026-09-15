/**
 * Everything that turns content into markup, and nothing that touches the
 * page. Pure string builders, so they can be exercised in Node without a
 * browser — which is how the cards are tested.
 */

export const escapeHtml = (value) =>
    String(value ?? '').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[c])

export const gradient = (service) => `linear-gradient(135deg, ${service.accent[0]}, ${service.accent[1]})`

export const pad = (n) => String(n).padStart(2, '0')

/**
 * Work out what a `src` string points at. A YouTube or Vimeo link needs an
 * iframe; anything else is treated as a file the <video> tag can play.
 */
export function parseSource(src) {
    const value = (src ?? '').trim()
    if (!value) return { kind: 'pending' }

    const youtube = value.match(/(?:youtube\.com\/(?:watch\?(?:.*&)?v=|embed\/|shorts\/|live\/)|youtu\.be\/)([\w-]{11})/i)
    if (youtube) {
        return {
            kind: 'youtube',
            embed: `https://www.youtube.com/embed/${youtube[1]}?autoplay=1&rel=0&modestbranding=1&playsinline=1`,
            poster: `https://i.ytimg.com/vi/${youtube[1]}/maxresdefault.jpg`,
            posterFallback: `https://i.ytimg.com/vi/${youtube[1]}/hqdefault.jpg`,
        }
    }

    const vimeo = value.match(/vimeo\.com\/(?:video\/)?(\d+)(?:\/([a-z0-9]+))?/i)
    if (vimeo) {
        const hash = vimeo[2] ? `&h=${vimeo[2]}` : ''
        return {
            kind: 'vimeo',
            embed: `https://player.vimeo.com/video/${vimeo[1]}?autoplay=1&dnt=1${hash}`,
            oembed: `https://vimeo.com/api/oembed.json?url=${encodeURIComponent(value)}&width=960`,
        }
    }

    const url = /^(https?:)?\/\//i.test(value) || value.startsWith('/') ? value : `/${value}`
    return { kind: 'file', url }
}

export const resolveAsset = (path) => (!path ? '' : /^(https?:)?\/\//i.test(path) || path.startsWith('/') ? path : `/${path}`)

/** Whether an entry is a course opened by link rather than a film. */
export const isLinkEntry = (video, service) => service?.kind === 'links' || Object.hasOwn(video, 'link')

/** The bare host of a link, for the caption: rise.articulate.com, not the whole URL. */
export function linkHost(href) {
    try {
        return new URL(href).hostname.replace(/^www\./, '')
    } catch {
        return ''
    }
}

/* ------------------------------------------------------------------
   Motifs: one figure per discipline, drawn from the discipline itself.
   ------------------------------------------------------------------ */

const motifs = {
    'articulate': () => `
        <circle cx="100" cy="100" r="74" class="motif-ring" stroke-dasharray="6 10" />
        <path class="motif-branch" d="M40 100h34m0 0 26-30h60m-86 30 26 30h60" />
        <circle class="motif-node motif-node-1" cx="40" cy="100" r="7" fill="currentColor" stroke="none" />
        <circle class="motif-node motif-node-2" cx="160" cy="70" r="7" fill="currentColor" stroke="none" />
        <circle class="motif-node motif-node-3" cx="160" cy="130" r="7" fill="currentColor" stroke="none" />`,

    'infographics': () => `
        <g class="motif-arcs" style="transform-origin: 100px 100px">
            <circle class="motif-arc motif-arc-1" cx="100" cy="100" r="62" stroke-width="9" stroke-dasharray="140 390" transform="rotate(-90 100 100)" />
            <circle class="motif-arc motif-arc-2" cx="100" cy="100" r="62" stroke-width="9" stroke-dasharray="100 390" transform="rotate(50 100 100)" opacity="0.7" />
            <circle class="motif-arc motif-arc-3" cx="100" cy="100" r="62" stroke-width="9" stroke-dasharray="70 390" transform="rotate(150 100 100)" opacity="0.45" />
        </g>
        <path class="motif-plot" d="M70 118l16-16 14 9 18-24 12 7" stroke-width="2.5" />
        <circle class="motif-dot motif-dot-1" cx="86" cy="102" r="4.5" fill="currentColor" stroke="none" />
        <circle class="motif-dot motif-dot-2" cx="118" cy="87" r="4.5" fill="currentColor" stroke="none" />
        <circle class="motif-dot motif-dot-3" cx="130" cy="94" r="4.5" fill="currentColor" stroke="none" />`,

    'animation': () => `
        <rect class="motif-frame motif-frame-3" x="34" y="46" width="108" height="108" rx="8" />
        <rect class="motif-frame motif-frame-2" x="48" y="46" width="108" height="108" rx="8" />
        <rect class="motif-frame motif-frame-1" x="62" y="46" width="108" height="108" rx="8" />
        <path class="motif-stroke" d="M74 128c14-46 42-58 70-24" stroke-width="2.5" />`,

    'ai-videos': () => {
        let cells = ''
        for (let r = 0; r < 6; r++) {
            for (let c = 0; c < 6; c++) {
                cells += `<rect x="${40 + c * 21}" y="${40 + r * 21}" width="13" height="13" rx="3" fill="currentColor" stroke="none" style="--i: ${r * 6 + c}" class="motif-cell" />`
            }
        }
        return `<g class="motif-grid">${cells}</g><path class="motif-scan" d="M32 40h136" stroke-width="2.5" />`
    },

    'smart-board': () => `
        <g class="motif-panels">
            <rect class="motif-panel motif-panel-1" x="26" y="58" width="64" height="46" rx="5" />
            <rect class="motif-panel motif-panel-2" x="106" y="58" width="64" height="46" rx="5" />
            <rect class="motif-panel motif-panel-3" x="26" y="118" width="64" height="46" rx="5" />
            <rect class="motif-panel motif-panel-4" x="106" y="118" width="64" height="46" rx="5" />
        </g>
        <path class="motif-sketch" d="M36 94c10-20 22-26 34-12M116 94c12-16 24-14 34 4M36 154c12-14 22-16 34-6M116 154c10-18 24-18 34 0" opacity="0.7" />`,

    'swayam': () => `
        <rect class="motif-screen" x="44" y="58" width="112" height="72" rx="8" />
        <path d="M100 130v18M76 150h48" />
        <path class="motif-play-mark" d="M92 82v24l20-12Z" fill="currentColor" stroke="none" />
        <path class="motif-wave motif-wave-1" d="M156 40a18 18 0 0 1 18 18" />
        <path class="motif-wave motif-wave-2" d="M156 26a32 32 0 0 1 32 32" opacity="0.7" />
        <path class="motif-wave motif-wave-3" d="M156 12a46 46 0 0 1 46 46" opacity="0.45" />`,

    'motion-graphics': () => {
        const bars = [46, 72, 98, 124, 150]
            .map((x, i) => `<rect class="motif-bar" x="${x}" y="70" width="14" height="60" rx="7" fill="currentColor" stroke="none" style="--i: ${i}" />`)
            .join('')
        return `<g class="motif-bars">${bars}</g><circle class="motif-orbit" cx="100" cy="100" r="82" stroke-dasharray="3 12" />`
    },
}

export const motif = (service) => `
    <div class="motif pointer-events-none absolute inset-0 -z-10 overflow-hidden" style="--from: ${service.accent[0]}; --to: ${service.accent[1]}" aria-hidden="true">
        <span class="motif-splash absolute -right-24 -top-24 size-[34rem] rounded-full blur-3xl"></span>
        <svg class="motif-art absolute right-4 top-1/2 h-56 w-56 -translate-y-1/2 opacity-[0.16] sm:right-10 sm:h-72 sm:w-72"
             viewBox="0 0 200 200" fill="none" stroke="currentColor" stroke-width="1.5"
             stroke-linecap="round" stroke-linejoin="round">
            ${(motifs[service.slug] ?? motifs['motion-graphics'])()}
        </svg>
    </div>`

/* ------------------------------------------------------------------
   Cards
   ------------------------------------------------------------------ */

const playIcon = `<svg viewBox="0 0 24 24" class="size-6 translate-x-0.5" fill="currentColor" aria-hidden="true"><path d="M8 5.5v13l11-6.5-11-6.5Z"/></svg>`

const openIcon = `<svg viewBox="0 0 24 24" class="size-6 transition-transform duration-500 ease-out" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17 17 7M9 7h8v8"/></svg>`

const cardShell = (service, index, interactive) => `
    class="sample-card reveal group relative flex flex-col overflow-hidden rounded-2xl bg-white ring-1 ring-ink-900/10 transition duration-500 ease-out hover:-translate-y-1.5 hover:ring-ink-900/20 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-teal ${interactive ? 'cursor-pointer' : ''}"
    style="--from: ${service.accent[0]}; --to: ${service.accent[1]}; --reveal-delay: ${(index % 3) * 90}ms"`

const cardWash = (service) => `
    <span aria-hidden="true" class="absolute inset-0 opacity-90" style="background: ${gradient(service)}"></span>
    <span aria-hidden="true" class="sample-splash absolute inset-0 mix-blend-screen"></span>`

const cardFinish = `
    <span aria-hidden="true" class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-transparent"></span>
    <span aria-hidden="true" class="sample-gloss pointer-events-none absolute inset-0"></span>
    <span aria-hidden="true" class="sample-shine pointer-events-none absolute inset-0"></span>`

const pendingLabel = (text) => `
    <span class="absolute inset-x-0 bottom-0 p-5">
        <span class="flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.2em] text-white/70">
            <svg class="size-3" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="6" cy="6" r="5"/></svg>
            ${text}
        </span>
    </span>`

/**
 * A course opened by link. There is no film to preview, so the panel carries
 * a faint course frame and an open-in-new-tab mark where the play button
 * would sit; the caption names the host so a visitor knows where they are
 * going before they click.
 */
export function linkCard(video, service, index) {
    // Only a web URL is ever placed in an href; anything else stays pending.
    const raw = (video.link ?? '').trim()
    const href = /^https?:\/\//i.test(raw) ? raw : ''
    const pending = !href
    const host = pending ? '' : linkHost(href)
    const title = escapeHtml(video.title)
    const client = escapeHtml(video.client)
    const poster = resolveAsset(video.poster)

    // A real screenshot of the course when one is supplied; otherwise a faint
    // Storyline-style slide stands in, so the card is never a flat blank.
    const frame = poster
        ? `<img src="${escapeHtml(poster)}" alt="" loading="lazy" decoding="async" class="absolute inset-0 size-full object-cover transition duration-700 ease-out group-hover:scale-[1.04]">`
        : `
        <svg aria-hidden="true" viewBox="0 0 320 180" class="absolute inset-0 size-full text-white/20 transition duration-700 ease-out group-hover:scale-[1.04] group-hover:text-white/30" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="56" y="30" width="208" height="120" rx="8" />
            <path d="M56 50h208" />
            <rect x="72" y="64" width="84" height="56" rx="4" opacity="0.7" />
            <path d="M172 72h76M172 88h60M172 104h70" opacity="0.7" />
            <path d="M72 134h176" stroke-width="3" opacity="0.35" />
            <path d="M72 134h64" stroke-width="3" class="link-progress" />
        </svg>`

    const overlay = pending
        ? pendingLabel('Link coming soon')
        : `<span class="absolute inset-0 grid place-items-center">
               <span class="link-button flex size-14 items-center justify-center rounded-full bg-white/15 text-white ring-1 ring-white/40 backdrop-blur-md transition duration-500 ease-out group-hover:scale-110 group-hover:bg-white/25">
                   ${openIcon}
               </span>
           </span>
           <span class="absolute inset-x-0 bottom-0 p-5">
               <span class="flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.2em] text-white/70">
                   <svg class="size-3" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3.5 8.5 8.5 3.5M5 3.5h3.5V7"/></svg>
                   Open course
               </span>
           </span>`

    const tag = pending ? 'figure' : 'a'
    const attrs = pending
        ? ''
        : `href="${escapeHtml(href)}" target="_blank" rel="noopener noreferrer" aria-label="Open ${title} in a new tab"`

    return `
        <${tag} data-card data-link-card ${attrs} ${cardShell(service, index, !pending)}>
            <div class="relative aspect-video w-full overflow-hidden bg-ink-900">
                ${cardWash(service)}
                ${frame}
                ${cardFinish}
                ${overlay}
            </div>

            <div class="flex flex-1 items-start justify-between gap-4 bg-white p-5">
                <div class="min-w-0">
                    <p class="truncate font-semibold text-ink-900 transition-colors duration-300 group-hover:text-brand-teal">${title}</p>
                    ${host ? `<p class="mt-0.5 truncate text-sm text-ink-900/50">${escapeHtml(host)}</p>` : client ? `<p class="mt-0.5 truncate text-sm text-ink-900/50">${client}</p>` : ''}
                </div>
                ${pending ? '' : `<span aria-hidden="true" class="shrink-0 text-ink-900/30 transition duration-300 group-hover:text-brand-teal">${openIcon.replace('size-6', 'size-4')}</span>`}
            </div>
        </${tag}>`
}

export function card(video, service, index) {
    if (isLinkEntry(video, service)) return linkCard(video, service, index)

    const source = parseSource(video.src)
    const pending = source.kind === 'pending'
    const poster = resolveAsset(video.poster) || source.poster || ''
    const title = escapeHtml(video.title)
    const client = escapeHtml(video.client)
    const duration = escapeHtml(video.duration)

    const posterImage = poster
        ? `<img src="${escapeHtml(poster)}" ${source.posterFallback ? `data-fallback="${escapeHtml(source.posterFallback)}"` : ''} alt="" loading="lazy" decoding="async" class="absolute inset-0 size-full object-cover transition duration-700 ease-out group-hover:scale-[1.04]">`
        : source.kind === 'vimeo'
            ? `<img data-vimeo-poster="${escapeHtml(source.oembed)}" alt="" loading="lazy" decoding="async" class="absolute inset-0 size-full object-cover opacity-0 transition duration-700 ease-out group-hover:scale-[1.04]">`
            : ''

    const preview = source.kind === 'file'
        ? `<video data-preview muted loop playsinline preload="none" aria-hidden="true" tabindex="-1" ${poster ? `poster="${escapeHtml(poster)}"` : ''}
                  class="absolute inset-0 size-full object-cover opacity-0 transition-opacity duration-500 group-hover:opacity-100">
               <source src="${escapeHtml(source.url)}">
           </video>`
        : ''

    const overlay = pending
        ? pendingLabel('Footage coming soon')
        : `<span class="absolute inset-0 grid place-items-center">
               <span class="play-button flex size-14 items-center justify-center rounded-full bg-white/15 text-white ring-1 ring-white/40 backdrop-blur-md transition duration-500 ease-out group-hover:scale-110 group-hover:bg-white/25">
                   ${playIcon}
               </span>
           </span>
           <span class="absolute inset-x-0 bottom-0 p-5">
               <span class="flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.2em] text-white/70">
                   <svg class="size-3" viewBox="0 0 12 12" fill="currentColor" aria-hidden="true"><path d="M3 1.5v9l7.5-4.5L3 1.5Z"/></svg>
                   Play
               </span>
           </span>`

    const interactive = pending
        ? ''
        : `data-play="${index}" role="button" tabindex="0" aria-label="Play ${title}"`

    return `
        <figure data-card ${interactive} ${cardShell(service, index, !pending)}>
            <div class="relative aspect-video w-full overflow-hidden bg-ink-900">
                ${cardWash(service)}
                ${posterImage}
                ${preview}
                ${cardFinish}
                ${overlay}
            </div>

            <figcaption class="flex flex-1 items-start justify-between gap-4 bg-white p-5">
                <div class="min-w-0">
                    <p class="truncate font-semibold text-ink-900 transition-colors duration-300 group-hover:text-brand-teal">${title}</p>
                    ${client ? `<p class="mt-0.5 truncate text-sm text-ink-900/50">${client}</p>` : ''}
                </div>
                ${duration ? `<span class="shrink-0 text-xs tabular-nums text-ink-900/40">${duration}</span>` : ''}
            </figcaption>
        </figure>`
}

/* ------------------------------------------------------------------
   Sections
   ------------------------------------------------------------------ */

export function section(service, position, videos) {
    const own = videos
        .map((video, index) => ({ video, index }))
        .filter(({ video }) => video.service === service.slug)

    const hasCategories = own.some(({ video }) => (video.category ?? '').trim() !== '')

    // Group under sub-headings in the order they first appear.
    const groups = new Map()
    own.forEach((entry) => {
        const key = (entry.video.category ?? '').trim()
        if (!groups.has(key)) groups.set(key, [])
        groups.get(key).push(entry)
    })

    const grids = [...groups.entries()].map(([category, entries]) => `
        ${hasCategories ? `
            <h3 class="reveal mt-14 flex items-center gap-4 text-2xl font-bold tracking-tight text-ink-900 sm:text-3xl">
                <span class="h-px w-6 shrink-0" style="background: ${gradient(service)}"></span>
                ${escapeHtml(category || 'More')}
            </h3>` : ''}
        <div class="${hasCategories ? 'mt-6' : 'mt-12'} grid gap-6 sm:grid-cols-2 lg:grid-cols-3 lg:gap-8">
            ${entries.map(({ video, index }) => card(video, service, index)).join('')}
        </div>`).join('')

    return `
        <section
            id="${service.slug}"
            class="relative isolate scroll-mt-8 overflow-hidden border-b border-ink-900/10 py-20 sm:py-28 ${position % 2 ? 'bg-ink-900/[0.02]' : ''}"
            aria-labelledby="${service.slug}-title"
        >
            ${motif(service)}

            <div class="relative mx-auto max-w-7xl px-5 sm:px-8">
                <div class="reveal grid gap-8 lg:grid-cols-[1fr_1.15fr] lg:items-end lg:gap-16">
                    <div>
                        <span class="flex items-center gap-3 text-[11px] font-semibold uppercase tracking-[0.28em] text-ink-900/35">
                            <span class="tabular-nums">${pad(position + 1)}</span>
                            <span class="h-px w-8" style="background: ${gradient(service)}"></span>
                        </span>

                        <h2 id="${service.slug}-title" class="mt-4 text-balance text-4xl font-bold leading-[1.05] tracking-tight text-ink-900 sm:text-5xl">
                            ${escapeHtml(service.name)}
                        </h2>

                        ${service.tagline ? `
                            <p class="mt-3 bg-clip-text text-lg font-semibold text-transparent" style="background-image: ${gradient(service)}">
                                ${escapeHtml(service.tagline)}
                            </p>` : ''}
                    </div>

                    ${service.description ? `
                        <p class="max-w-xl text-base leading-relaxed text-ink-900/60 sm:text-lg">
                            ${escapeHtml(service.description)}
                        </p>` : ''}
                </div>

                ${grids}
            </div>
        </section>`
}
