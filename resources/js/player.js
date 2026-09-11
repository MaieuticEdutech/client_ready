/**
 * Click a card, and its film opens in the player with sound and controls.
 *
 * A native <dialog>: the browser handles focus trapping, Escape and the
 * backdrop. The player is sized to the viewport, so it fits whatever the
 * client is watching on, and the film's own controls offer true fullscreen.
 */
export function initPlayer() {
    const dialog = document.getElementById('player')
    if (!dialog || dialog.dataset.ready) return
    dialog.dataset.ready = 'true'

    const frame = dialog.querySelector('[data-player-frame]')
    const title = dialog.querySelector('[data-player-title]')
    const meta = dialog.querySelector('[data-player-meta]')
    let opener = null

    const open = (card) => {
        const { playSrc, playKind, playTitle, playMeta, playFrom, playPoster } = card.dataset
        if (!playSrc) return

        opener = card

        // Still the silent preview so two copies never play at once.
        card.querySelector('video')?.pause()

        if (playKind === 'embed') {
            frame.innerHTML = `<iframe src="${escape(playSrc)}" title="${escape(playTitle)}" allow="autoplay; fullscreen; picture-in-picture; encrypted-media" allowfullscreen referrerpolicy="strict-origin-when-cross-origin"></iframe>`
        } else {
            frame.innerHTML = `<video controls autoplay playsinline ${playPoster ? `poster="${escape(playPoster)}"` : ''} src="${escape(playSrc)}"></video>`
        }

        title.textContent = playTitle ?? ''
        meta.textContent = playMeta ?? ''
        dialog.style.setProperty('--from', playFrom || 'transparent')
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
        if (card) open(card)
    })

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Enter' && event.key !== ' ') return
        const card = event.target.closest?.('[data-play]')
        if (!card) return
        event.preventDefault()
        open(card)
    })
}

const escape = (value) =>
    String(value ?? '').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[c])
