/**
 * Numbers marked with data-count climb to their value the first time they
 * scroll into view, once, so the about panel reads as arriving rather than
 * merely printed. Nothing runs for anyone who has asked for stillness.
 */
export function initCountUp() {
    const items = document.querySelectorAll('[data-count]')
    if (!items.length) return

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return

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

function climb(el) {
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
