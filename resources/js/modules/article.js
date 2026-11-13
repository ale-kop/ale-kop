/*------------------------------------------------
    Página de post (posts/show)
    - [data-article-progress]: barra de leitura no topo,
      calculada sobre o elemento [data-article-body].
    - [data-copy-link]: copia a URL da página.
    - [data-article-popup]: convite de inscrição aberto ao rolar
      POPUP_TRIGGER do texto (estilo Substack). Ao fechar, fica
      oculto por POPUP_SNOOZE_DAYS (localStorage).
    init() é idempotente.
-------------------------------------------------*/
const POPUP_TRIGGER = 0.4
const POPUP_SNOOZE_DAYS = 30
const POPUP_STORAGE_KEY = 'article-popup-dismissed-at'

export function init() {
    initProgress()
    initCopyLink()
    initSubscribePopup()
}

function initProgress() {
    const bar = document.querySelector('[data-article-progress] i')
    const body = document.querySelector('[data-article-body]')
    if (!bar || !body || bar.dataset.bound === 'true') return

    const update = () => {
        const rect = body.getBoundingClientRect()
        // Texto mais curto que a tela (ou imagens ainda carregando): evita dividir por zero/negativo.
        const total = Math.max(1, rect.height - window.innerHeight * 0.6)
        const done = (-rect.top + window.innerHeight * 0.2) / total
        bar.style.width = Math.min(100, Math.max(0, done * 100)) + '%'
    }

    window.addEventListener('scroll', update, { passive: true })
    window.addEventListener('resize', update, { passive: true })
    window.addEventListener('load', update)
    body.querySelectorAll('img').forEach((img) => img.addEventListener('load', update, { once: true }))
    update()
    bar.dataset.bound = 'true'
}

function initCopyLink() {
    document.querySelectorAll('[data-copy-link]').forEach((button) => {
        if (button.dataset.bound === 'true') return

        const label = button.textContent.trim()
        button.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(window.location.href)
                button.textContent = 'Link copiado'
            } catch (e) {
                button.textContent = 'Não foi possível copiar'
            }
            setTimeout(() => { button.textContent = label }, 2000)
        })
        button.dataset.bound = 'true'
    })
}

function initSubscribePopup() {
    const popup = document.querySelector('[data-article-popup]')
    const body = document.querySelector('[data-article-body]')
    if (!popup || !body || popup.dataset.bound === 'true') return
    popup.dataset.bound = 'true'

    popup.querySelectorAll('[data-article-popup-close]').forEach((button) => {
        button.addEventListener('click', () => Modal.close(popup.id))
    })
    popup.addEventListener('click', (event) => {
        if (event.target === popup) Modal.close(popup.id)
    })
    // Fechou de qualquer jeito (X, Esc, fundo, inscrição concluída): não insiste por um tempo.
    popup.addEventListener('close', () => storageSet(POPUP_STORAGE_KEY, String(Date.now())))

    if (wasDismissedRecently()) return

    const check = () => {
        const rect = body.getBoundingClientRect()
        const read = (window.innerHeight - rect.top) / Math.max(1, rect.height)
        if (read < POPUP_TRIGGER) return

        window.removeEventListener('scroll', check)
        // Se a pessoa já chegou ao box do fim do post, não precisa do pop-up.
        const inlineBox = document.querySelector('.article-subscribe')
        if (inlineBox && inlineBox.getBoundingClientRect().top < window.innerHeight) return
        if (document.querySelector('dialog[open]')) return

        Modal.open(popup.id)
    }

    window.addEventListener('scroll', check, { passive: true })
}

function wasDismissedRecently() {
    const at = Number(storageGet(POPUP_STORAGE_KEY))
    return at > 0 && Date.now() - at < POPUP_SNOOZE_DAYS * 24 * 60 * 60 * 1000
}

function storageGet(key) {
    try { return window.localStorage.getItem(key) } catch (e) { return null }
}

function storageSet(key, value) {
    try { window.localStorage.setItem(key, value) } catch (e) { /* modo privado: ignora */ }
}
