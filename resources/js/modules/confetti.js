/*------------------------------------------------
    Confete em tela cheia (canvas, sem dependências).
    launchConfetti({ colors, count, spawnMs })
    - Cobre o site todo e não captura cliques (pointer-events: none).
    - O canvas vai para o top-layer via popover, para ficar acima de <dialog>
      (um pop-up modal cobriria um canvas comum).
    - Respeita prefers-reduced-motion: nesse caso não anima.
-------------------------------------------------*/
const DEFAULT_COLORS = ['#4087C2', '#144A6E', '#D95F02', '#F4B740', '#7FB5E0']

export function launchConfetti({ colors = DEFAULT_COLORS, count = 200, spawnMs = 1500 } = {}) {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return

    const canvas = document.createElement('canvas')
    canvas.className = 'confetti-canvas'
    canvas.setAttribute('aria-hidden', 'true')
    const usePopover = typeof canvas.showPopover === 'function'
    if (usePopover) canvas.setAttribute('popover', 'manual')
    document.body.append(canvas)
    if (usePopover) {
        try { canvas.showPopover() } catch (e) { /* sem top-layer: o z-index do CSS cobre a página */ }
    }

    const ctx = canvas.getContext('2d')
    const dpr = Math.min(window.devicePixelRatio || 1, 2)
    let width = 0
    let height = 0

    const resize = () => {
        width = window.innerWidth
        height = window.innerHeight
        canvas.width = width * dpr
        canvas.height = height * dpr
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0)
    }
    resize()
    window.addEventListener('resize', resize)

    const pieces = []
    let spawned = 0
    const start = performance.now()
    let last = start

    const spawn = () => ({
        x: Math.random() * width,
        y: -20 - Math.random() * height * 0.1,
        size: 6 + Math.random() * 7,
        ratio: 0.4 + Math.random() * 0.6,
        vy: 200 + Math.random() * 200,
        vx: (Math.random() - 0.5) * 70,
        sway: Math.random() * Math.PI * 2,
        swaySpeed: 2 + Math.random() * 3,
        swayAmp: 20 + Math.random() * 40,
        rot: Math.random() * Math.PI * 2,
        spin: (Math.random() - 0.5) * 8,
        color: colors[Math.floor(Math.random() * colors.length)],
        round: Math.random() < 0.25,
    })

    const finish = () => {
        window.removeEventListener('resize', resize)
        canvas.remove()
    }

    const frame = (now) => {
        const dt = Math.min(0.05, (now - last) / 1000)
        last = now

        // rajada inicial (40%) e o resto escorrendo ao longo de spawnMs
        const progress = Math.min(1, (now - start) / spawnMs)
        const target = Math.min(count, Math.floor((0.4 + 0.6 * progress) * count))
        while (spawned < target) {
            pieces.push(spawn())
            spawned++
        }

        ctx.clearRect(0, 0, width, height)

        for (let i = pieces.length - 1; i >= 0; i--) {
            const p = pieces[i]
            p.y += p.vy * dt
            p.sway += p.swaySpeed * dt
            p.x += p.vx * dt + Math.cos(p.sway) * p.swayAmp * dt
            p.rot += p.spin * dt

            if (p.y > height + 30) {
                pieces.splice(i, 1)
                continue
            }

            ctx.save()
            ctx.translate(p.x, p.y)
            ctx.rotate(p.rot)
            ctx.scale(1, Math.cos(p.sway * 1.3)) // vira no ar
            ctx.fillStyle = p.color
            if (p.round) {
                ctx.beginPath()
                ctx.arc(0, 0, p.size / 2.4, 0, Math.PI * 2)
                ctx.fill()
            } else {
                ctx.fillRect(-p.size / 2, (-p.size * p.ratio) / 2, p.size, p.size * p.ratio)
            }
            ctx.restore()
        }

        if (spawned < count || pieces.length > 0) requestAnimationFrame(frame)
        else finish()
    }

    requestAnimationFrame(frame)
}
