import hljs from 'highlight.js'

export function init() {
    document.querySelectorAll('.html-content pre code').forEach(el => {
        if (el.dataset.highlighted) return
        hljs.highlightElement(el)
    })
}
