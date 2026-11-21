/*------------------------------------------------
    Alcance de uma campanha ([data-nl-audience], <x-newsletter.list-picker>)
    A cada lista marcada/desmarcada, pergunta ao servidor quantas pessoas
    ativas recebem (sem contar duas vezes quem está em várias listas).
    init() é idempotente.
-------------------------------------------------*/
export function init() {
    document.querySelectorAll('[data-nl-audience]').forEach(bind)
}

function bind(root) {
    if (root.dataset.bound === 'true') return
    root.dataset.bound = 'true'

    const total = root.querySelector('[data-nl-audience-total]')
    const boxes = [...root.querySelectorAll('[data-nl-audience-list]')]
    let request = 0

    const refresh = async () => {
        const ids = boxes.filter((box) => box.checked).map((box) => box.value)
        if (ids.length === 0) {
            total.textContent = 'Marque pelo menos uma lista.'
            return
        }

        const current = ++request
        const params = new URLSearchParams(ids.map((id) => ['list_ids[]', id]))
        try {
            const response = await fetch(`${root.dataset.audienceUrl}?${params}`, { headers: { Accept: 'application/json' } })
            const body = await response.json()
            if (current !== request) return // resposta antiga chegando depois de uma nova
            total.textContent = `Vai para ${body.count.toLocaleString('pt-BR')} ${body.count === 1 ? 'pessoa' : 'pessoas'}.`
        } catch (e) {
            total.textContent = ''
        }
    }

    boxes.forEach((box) => box.addEventListener('change', refresh))
    refresh()
}
