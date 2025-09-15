export function init() {
    const containers = document.querySelectorAll('.html-content')
    containers.forEach(container => {
        container.querySelectorAll('pre').forEach(pre => {
            if (pre.dataset.copyAttached === 'true') return

            const btn = document.createElement('button')
            btn.type = 'button'
            btn.className = 'code-copy-btn cursor-pointer'
            btn.textContent = 'Copiar'
            btn.addEventListener('click', async () => {
                const code = pre.querySelector('code')
                const text = code ? code.innerText : pre.innerText
                try {
                    await navigator.clipboard.writeText(text)
                    btn.textContent = 'Copiado!'
                    btn.classList.add('copied')
                    setTimeout(() => { btn.textContent = 'Copiar'; btn.classList.remove('copied') }, 1500)
                } catch (e) {
                    btn.textContent = 'Error'
                    setTimeout(() => { btn.textContent = 'Copiar' }, 1500)
                }
            })
            pre.appendChild(btn)
            pre.dataset.copyAttached = 'true'
        })
    })
}

