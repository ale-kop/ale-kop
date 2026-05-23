// Listener at module level — init() is a no-op so multiple initAllModules() calls are safe
document.addEventListener('click', (e) => {
    const opener = e.target.closest('[data-side-panel-open]')
    const closer = e.target.closest('[data-side-panel-close]')

    if (opener) {
        const targetSel  = opener.getAttribute('data-side-panel-open')
        const overlaySel = opener.getAttribute('data-side-panel-overlay')
        const panel      = document.querySelector(targetSel)
        const overlay    = (overlaySel && overlaySel !== 'false') ? document.querySelector(overlaySel) : null
        if (!panel) return
        e.preventDefault()
        panel.classList.remove('translate-x-full', '-translate-x-full', 'opacity-0', 'scale-95')
        if (overlay) {
            overlay.classList.remove('opacity-0', 'pointer-events-none')
        }
        return
    }

    if (closer) {
        const targetSel  = closer.getAttribute('data-side-panel-target')
        const overlaySel = closer.getAttribute('data-side-panel-overlay')
        const panel      = document.querySelector(targetSel)
        const overlay    = (overlaySel && overlaySel !== 'false') ? document.querySelector(overlaySel) : null
        if (!panel) return
        e.preventDefault()
        panel.classList.add('translate-x-full', 'opacity-0', 'scale-95')
        if (overlay) {
            overlay.classList.add('opacity-0', 'pointer-events-none')
        }
    }
}, true)

export function init() {}
