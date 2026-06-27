let el = null
let lbImg = null
let lbClose = null
let lbPrev = null
let lbNext = null
let lbImages = []
let lbCurrent = 0

function getLargeSrc(img) {
    return img.dataset.largeSrc || img.src
}

function build() {
    if (el) return

    el = document.createElement('div')
    el.id = 'image-lightbox'
    el.setAttribute('role', 'dialog')
    el.setAttribute('aria-modal', 'true')
    el.innerHTML = `
        <div id="lb-backdrop"></div>
        <div id="lb-wrap">
            <button type="button" id="lb-prev" aria-label="Anterior">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M7.72 12.53a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 1 1 1.06 1.06L9.31 12l6.97 6.97a.75.75 0 1 1-1.06 1.06l-7.5-7.5Z" clip-rule="evenodd"/></svg>
            </button>
            <div id="lb-frame">
                <button type="button" id="lb-close" aria-label="Fechar">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
                </button>
                <img id="lb-img" src="" alt="">
            </div>
            <button type="button" id="lb-next" aria-label="Próxima">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M16.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 0 1-1.06-1.06L14.69 12 7.72 5.03a.75.75 0 0 1 1.06-1.06l7.5 7.5Z" clip-rule="evenodd"/></svg>
            </button>
        </div>
    `
    document.body.appendChild(el)

    lbImg   = document.getElementById('lb-img')
    lbClose = document.getElementById('lb-close')
    lbPrev  = document.getElementById('lb-prev')
    lbNext  = document.getElementById('lb-next')

    document.getElementById('lb-backdrop').addEventListener('click', close)
    lbClose.addEventListener('click', close)
    lbPrev.addEventListener('click', () => navigate(-1))
    lbNext.addEventListener('click', () => navigate(1))
    document.addEventListener('keydown', onKey)
}

function open(images, idx) {
    build()
    lbImages = images
    lbCurrent = idx
    lbImg.src = getLargeSrc(images[idx])
    lbImg.alt = images[idx].alt || ''
    updateNav()
    el.classList.add('is-open')
    document.body.style.overflow = 'hidden'
}

function close() {
    if (!el) return
    el.classList.remove('is-open')
    document.body.style.overflow = ''
    setTimeout(() => { if (lbImg) lbImg.src = '' }, 200)
}

function navigate(dir) {
    const next = lbCurrent + dir
    if (next < 0 || next >= lbImages.length) return
    lbCurrent = next
    lbImg.src = getLargeSrc(lbImages[lbCurrent])
    lbImg.alt = lbImages[lbCurrent].alt || ''
    updateNav()
}

function updateNav() {
    lbPrev.style.visibility = lbCurrent === 0 ? 'hidden' : 'visible'
    lbNext.style.visibility = lbCurrent === lbImages.length - 1 ? 'hidden' : 'visible'
}

function onKey(e) {
    if (!el?.classList.contains('is-open')) return
    if (e.key === 'Escape')     close()
    if (e.key === 'ArrowLeft')  navigate(-1)
    if (e.key === 'ArrowRight') navigate(1)
}

export function init() {
    const imgs = [...document.querySelectorAll('.html-content figure img')]
    imgs.forEach((img, idx) => {
        if (img.dataset.lightboxAttached) return
        img.dataset.lightboxAttached = '1'
        img.addEventListener('click', () => open(imgs, idx))
    })
}
