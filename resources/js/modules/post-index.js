export function init() {
    const panel = document.getElementById('post-index-panel')
    if (!panel) return

    // Auto-scroll active item into view (both desktop and mobile lists)
    const containers = [panel, document.querySelector('aside.sticky')].filter(Boolean)
    containers.forEach(container => {
        const active = container.querySelector('[data-active]')
        if (active && typeof active.scrollIntoView === 'function') {
            active.scrollIntoView({block: 'center'})
        }
    })
}
