import './style.css'
import { videos, services, stats, company } from './content.js'

/* ------------------------------------------------------------------
   Helpers
   ------------------------------------------------------------------ */

const escapeHtml = (value) =>
    String(value ?? '').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[c])

const gradient = (service) => `linear-gradient(135deg, ${service.accent[0]}, ${service.accent[1]})`

const pad = (n) => String(n).padStart(2, '0')

const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)')

/**
 * Work out what a `src` string points at. A YouTube or Vimeo link needs an
 * iframe; anything else is treated as a file the <video> tag can play.
 */
function parseSource(src) {
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

const resolveAsset = (path) => (!path ? '' : /^(https?:)?\/\//i.test(path) || path.startsWith('/') ? path : `/${path}`)

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

    'motion-graphics': () => {
        const bars = [46, 72, 98, 124, 150]
            .map((x, i) => `<rect class="motif-bar" x="${x}" y="70" width="14" height="60" rx="7" fill="currentColor" stroke="none" style="--i: ${i}" />`)
            .join('')
        return `<g class="motif-bars">${bars}</g><circle class="motif-orbit" cx="100" cy="100" r="82" stroke-dasharray="3 12" />`
    },
}

const motif = (service) => `
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

function card(video, service, index) {
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
        ? `<span class="absolute inset-x-0 bottom-0 p-5">
               <span class="flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.2em] text-white/70">
                   <svg class="size-3" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="6" cy="6" r="5"/></svg>
                   Footage coming soon
               </span>
           </span>`
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
        <figure
            data-card
            ${interactive}
            class="sample-card reveal group relative flex flex-col overflow-hidden rounded-2xl bg-white ring-1 ring-ink-900/10 transition duration-500 ease-out hover:-translate-y-1.5 hover:ring-ink-900/20 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-teal ${pending ? '' : 'cursor-pointer'}"
            style="--from: ${service.accent[0]}; --to: ${service.accent[1]}; --reveal-delay: ${(index % 3) * 90}ms"
        >
            <div class="relative aspect-video w-full overflow-hidden bg-ink-900">
                <span aria-hidden="true" class="absolute inset-0 opacity-90" style="background: ${gradient(service)}"></span>
                <span aria-hidden="true" class="sample-splash absolute inset-0 mix-blend-screen"></span>
                ${posterImage}
                ${preview}
                <span aria-hidden="true" class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-transparent"></span>
                <span aria-hidden="true" class="sample-gloss pointer-events-none absolute inset-0"></span>
                <span aria-hidden="true" class="sample-shine pointer-events-none absolute inset-0"></span>
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
   Stat cards: built like a sample card's poster panel. One gradient tile
   per figure, with the cursor wash, gloss and shine, and the figure and
   its label sitting where a film's caption would.
   ------------------------------------------------------------------ */

function statCard(stat, index) {
    const service = { accent: stat.accent }

    return `
        <div
            data-card
            class="sample-card reveal group relative isolate flex aspect-video flex-col justify-end overflow-hidden rounded-2xl bg-ink-900 p-4 ring-1 ring-white/15 transition duration-500 ease-out hover:-translate-y-1.5 hover:ring-white/30 sm:aspect-[4/3]"
            style="--from: ${stat.accent[0]}; --to: ${stat.accent[1]}; --reveal-delay: ${420 + index * 90}ms"
        >
            <span aria-hidden="true" class="absolute inset-0 -z-10 opacity-90" style="background: ${gradient(service)}"></span>
            <span aria-hidden="true" class="sample-splash absolute inset-0 -z-10 mix-blend-screen"></span>
            <span aria-hidden="true" class="absolute inset-0 -z-10 bg-gradient-to-t from-black/80 via-black/25 to-transparent"></span>
            <span aria-hidden="true" class="sample-gloss pointer-events-none absolute inset-0 -z-10"></span>
            <span aria-hidden="true" class="sample-shine pointer-events-none absolute inset-0 -z-10"></span>

            <dt class="order-2 mt-2 text-[10px] font-medium uppercase leading-snug tracking-[0.14em] text-white/60">${escapeHtml(stat.label)}</dt>
            <dd class="order-1 bg-gradient-to-r from-brand-mint to-brand-aqua bg-clip-text font-serif text-4xl font-semibold tabular-nums leading-none text-transparent">
                <span data-count="${escapeHtml(stat.value)}">${escapeHtml(stat.value)}</span>${escapeHtml(stat.suffix)}
            </dd>
        </div>`
}

/* ------------------------------------------------------------------
   Sections
   ------------------------------------------------------------------ */

function section(service, position) {
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

/* ------------------------------------------------------------------
   Footer
   ------------------------------------------------------------------ */

function footer() {
    const link = (label, href) => {
        const url = href.startsWith('/') ? company.site + href : href
        return `<li><a href="${escapeHtml(url)}" class="text-white/70 transition hover:text-white">${escapeHtml(label)}</a></li>`
    }

    const column = (heading) => `
        <nav aria-label="${escapeHtml(heading)}">
            <h3 class="text-[11px] font-bold uppercase tracking-[0.2em] text-white">${escapeHtml(heading)}</h3>
            <ul class="mt-5 space-y-3 text-sm">
                ${company.columns[heading].map(([label, href]) => link(label, href)).join('')}
            </ul>
        </nav>`

    return `
        <footer class="bg-ink-800 text-white" aria-labelledby="footer-heading">
            <h2 id="footer-heading" class="sr-only">About ${escapeHtml(company.name)}</h2>

            <div class="mx-auto max-w-7xl px-5 pb-12 pt-16 sm:px-8 sm:pt-20">
                <div class="grid gap-12 md:grid-cols-2 lg:grid-cols-[1.2fr_1.3fr_1fr_1fr] lg:gap-10">
                    <div class="max-w-xs">
                        <a href="/" class="inline-block">
                            <img src="/images/maieutic-logo-white.png" alt="Maieutic Edutech" class="h-12 w-auto" width="2706" height="910" loading="lazy">
                        </a>

                        <p class="mt-6 text-sm leading-relaxed text-white/70">${escapeHtml(company.blurb)}</p>

                        <ul class="mt-7 flex gap-3" aria-label="Follow Maieutic">
                            ${company.socials.map(([label, href, path]) => `
                                <li>
                                    <a href="${escapeHtml(href)}" target="_blank" rel="noopener" aria-label="${escapeHtml(label)}"
                                       class="flex size-10 items-center justify-center rounded-lg border border-white/15 text-white/70 transition hover:border-brand-mint/60 hover:text-brand-mint">
                                        <svg viewBox="0 0 24 24" class="size-4" fill="currentColor" aria-hidden="true"><path d="${path}" /></svg>
                                    </a>
                                </li>`).join('')}
                        </ul>

                        <hr class="my-8 border-white/10">

                        <h3 class="text-[11px] font-bold uppercase tracking-[0.2em] text-white">Address</h3>
                        <address class="mt-4 text-sm not-italic leading-relaxed text-white/70">
                            ${company.address.map(escapeHtml).join('<br>')}
                        </address>

                        <ul class="mt-6 space-y-3 text-sm">
                            <li>
                                <a href="mailto:${escapeHtml(company.email)}" class="flex items-center gap-2.5 text-white/80 transition hover:text-brand-mint">
                                    <svg viewBox="0 0 24 24" class="size-4 shrink-0 text-brand-mint" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2" /><path d="m3 7 9 6 9-6" /></svg>
                                    ${escapeHtml(company.email)}
                                </a>
                            </li>
                            <li>
                                <a href="${escapeHtml(company.phoneHref)}" class="flex items-center gap-2.5 text-white/80 transition hover:text-brand-mint">
                                    <svg viewBox="0 0 24 24" class="size-4 shrink-0 text-brand-mint" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2Z" /></svg>
                                    ${escapeHtml(company.phone)}
                                </a>
                            </li>
                        </ul>
                    </div>

                    ${column('Solutions')}
                    ${column('Company')}

                    <div class="space-y-10">
                        ${column('Resources')}
                        ${column('Contact Us')}
                    </div>
                </div>

                <div class="mt-14 border-t border-white/10 pt-6 text-xs text-white/45">
                    &copy; ${new Date().getFullYear()} ${escapeHtml(company.name)} &mdash; All Rights Reserved
                </div>
            </div>
        </footer>`
}

/* ------------------------------------------------------------------
   Behaviour
   ------------------------------------------------------------------ */

/** Elements rise into place once, as they first enter the viewport. */
function initReveal() {
    const items = document.querySelectorAll('.reveal:not(.is-revealed)')
    if (!items.length) return

    if (reducedMotion.matches) {
        items.forEach((el) => el.classList.add('is-revealed'))
        return
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return
                entry.target.classList.add('is-revealed')
                observer.unobserve(entry.target)
            })
        },
        { rootMargin: '0px 0px -12% 0px', threshold: 0.1 }
    )

    items.forEach((el) => observer.observe(el))
}

/**
 * Numbers marked with data-count climb to their value the first time they
 * scroll into view, once, so the about panel reads as arriving rather than
 * merely printed. Nothing runs for anyone who has asked for stillness.
 */
function initCountUp() {
    const items = document.querySelectorAll('[data-count]')
    if (!items.length || reducedMotion.matches) return

    const climb = (el) => {
        const target = Number(el.dataset.count)
        if (!Number.isFinite(target)) return

        // A year counts up from a little way back; a small figure from zero.
        const from = target > 100 ? target - 60 : 0
        const duration = 1600
        const start = performance.now()
        const easeOut = (t) => 1 - Math.pow(1 - t, 3)

        const tick = (now) => {
            const t = Math.min((now - start) / duration, 1)
            el.textContent = String(Math.round(from + (target - from) * easeOut(t)))
            if (t < 1) requestAnimationFrame(tick)
        }

        requestAnimationFrame(tick)
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return
                observer.unobserve(entry.target)
                climb(entry.target)
            })
        },
        { threshold: 0.4 }
    )

    items.forEach((el) => observer.observe(el))
}

/** The colour wash follows the cursor; a film previews silently on hover. */
function initCards() {
    document.querySelectorAll('[data-card]').forEach((card) => {
        card.addEventListener('pointermove', (event) => {
            const rect = card.getBoundingClientRect()
            card.style.setProperty('--x', `${((event.clientX - rect.left) / rect.width) * 100}%`)
            card.style.setProperty('--y', `${((event.clientY - rect.top) / rect.height) * 100}%`)
        })

        const film = card.querySelector('video[data-preview]')
        if (film) {
            card.addEventListener('pointerenter', () => {
                if (!reducedMotion.matches) film.play().catch(() => {})
            })
            card.addEventListener('pointerleave', () => {
                film.pause()
                film.currentTime = 0
            })
        }

        // YouTube's largest thumbnail does not exist for every video; drop to the next size.
        const poster = card.querySelector('img[data-fallback]')
        if (poster) {
            poster.addEventListener('error', () => {
                poster.src = poster.dataset.fallback
                delete poster.dataset.fallback
            }, { once: true })
            // maxresdefault returns a 120x90 placeholder rather than a 404 for some videos.
            poster.addEventListener('load', () => {
                if (poster.naturalWidth <= 120 && poster.dataset.fallback) {
                    poster.src = poster.dataset.fallback
                    delete poster.dataset.fallback
                }
            })
        }

        // Vimeo thumbnails come from its public oEmbed endpoint, no key needed.
        const vimeo = card.querySelector('img[data-vimeo-poster]')
        if (vimeo) {
            fetch(vimeo.dataset.vimeoPoster)
                .then((r) => (r.ok ? r.json() : Promise.reject()))
                .then((data) => {
                    if (!data.thumbnail_url) return
                    vimeo.src = data.thumbnail_url
                    vimeo.classList.remove('opacity-0')
                })
                .catch(() => {})
        }
    })
}

/** Click a card, and its film opens in the player with sound and controls. */
function initPlayer() {
    const dialog = document.getElementById('player')
    const frame = dialog.querySelector('[data-player-frame]')
    const title = dialog.querySelector('[data-player-title]')
    const meta = dialog.querySelector('[data-player-meta]')
    let opener = null

    const open = (index, from) => {
        const video = videos[index]
        if (!video) return
        const service = services.find((s) => s.slug === video.service)
        const source = parseSource(video.src)
        if (source.kind === 'pending') return

        opener = from

        if (source.kind === 'file') {
            const poster = resolveAsset(video.poster)
            frame.innerHTML = `<video controls autoplay playsinline ${poster ? `poster="${escapeHtml(poster)}"` : ''} src="${escapeHtml(source.url)}"></video>`
        } else {
            frame.innerHTML = `<iframe src="${escapeHtml(source.embed)}" title="${escapeHtml(video.title)}" allow="autoplay; fullscreen; picture-in-picture; encrypted-media" allowfullscreen referrerpolicy="strict-origin-when-cross-origin"></iframe>`
        }

        title.textContent = video.title
        meta.textContent = [service?.name, video.client, video.duration].filter(Boolean).join('  ·  ')
        dialog.style.setProperty('--from', service?.accent[0] ?? 'transparent')
        dialog.showModal()
        document.body.style.overflow = 'hidden'
    }

    const close = () => {
        if (dialog.open) dialog.close()
    }

    dialog.addEventListener('close', () => {
        frame.innerHTML = ''
        document.body.style.overflow = ''
        opener?.focus?.()
        opener = null
    })

    // Backdrop click: the dialog itself is the target only outside its children.
    dialog.addEventListener('click', (event) => {
        if (event.target === dialog) close()
    })

    dialog.querySelector('[data-player-close]').addEventListener('click', close)

    document.addEventListener('click', (event) => {
        const card = event.target.closest('[data-play]')
        if (card) open(Number(card.dataset.play), card)
    })

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Enter' && event.key !== ' ') return
        const card = event.target.closest?.('[data-play]')
        if (!card) return
        event.preventDefault()
        open(Number(card.dataset.play), card)
    })
}

/* ------------------------------------------------------------------
   Render
   ------------------------------------------------------------------ */

document.getElementById('jump-list').innerHTML = services
    .map((service) => `
        <a href="#${service.slug}" class="group/chip inline-flex items-center gap-2.5 rounded-full bg-ink-900/[0.07] px-4 py-2.5 text-sm font-semibold text-ink-900/80 ring-1 ring-ink-900/10 transition duration-300 ease-out hover:-translate-y-0.5 hover:bg-ink-900 hover:text-white hover:shadow-lg hover:shadow-ink-900/15 hover:ring-ink-900">
            <span aria-hidden="true" class="size-2 shrink-0 rounded-full ring-2 ring-white/70 transition group-hover/chip:scale-125" style="background: linear-gradient(135deg, ${service.accent[0]}, ${service.accent[1]})"></span>
            ${escapeHtml(service.name)}
        </a>`)
    .join('')

document.getElementById('stats').innerHTML = stats.map(statCard).join('')
document.getElementById('work').innerHTML = services.map(section).join('')
document.getElementById('site-footer').innerHTML = footer()

initReveal()
initCountUp()
initCards()
initPlayer()
