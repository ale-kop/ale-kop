export function init() {
    document.addEventListener('click', (e) => {
        const opener = e.target.closest('[data-side-panel-open]')
        const closer = e.target.closest('[data-side-panel-close]')

        if (opener) {
            const targetSel = opener.getAttribute('data-side-panel-open')
            const overlaySel = opener.getAttribute('data-side-panel-overlay')
            const panel = document.querySelector(targetSel)
            const overlay = overlaySel ? document.querySelector(overlaySel) : null
            if (!panel) return
            e.preventDefault()
            panel.classList.remove('translate-x-full')
            panel.classList.remove('-translate-x-full')
            panel.classList.remove('opacity-0')
            panel.classList.remove('scale-95')
            if (overlay) {
                overlay.classList.remove('opacity-0')
                overlay.classList.remove('pointer-events-none')
            }
            return
        }

        if (closer) {
            const targetSel = closer.getAttribute('data-side-panel-target')
            const overlaySel = closer.getAttribute('data-side-panel-overlay')
            const panel = document.querySelector(targetSel)
            const overlay = overlaySel ? document.querySelector(overlaySel) : null
            if (!panel) return
            e.preventDefault()
            panel.classList.add('translate-x-full')
            panel.classList.add('-translate-x-full')
            panel.classList.add('opacity-0')
            panel.classList.add('scale-95')
            if (overlay) {
                overlay.classList.add('opacity-0')
                overlay.classList.add('pointer-events-none')
            }
            return
        }
    }, true)
}

