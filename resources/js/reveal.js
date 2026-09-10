/**
 * Elements rise into place once, as they first enter the viewport.
 *
 * IntersectionObserver rather than a scroll handler: the browser does the
 * work off the main thread, and each element is unobserved once it has
 * played, so nothing accumulates as the page grows.
 */
export function initReveal() {
    const items = document.querySelectorAll('.reveal')
    if (!items.length) return

    // No observer and no transition for anyone who has asked for stillness.
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
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
