import './style.css'
import { videos, services, stats, company } from './content.js'
import { escapeHtml, gradient, parseSource, resolveAsset, section } from './render.js'

const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)')

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
document.getElementById('work').innerHTML = services.map((service, position) => section(service, position, videos)).join('')
document.getElementById('site-footer').innerHTML = footer()

initReveal()
initCountUp()
initCards()
initPlayer()
