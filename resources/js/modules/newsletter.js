/*------------------------------------------------
    Inscrição na newsletter em duas etapas ([data-newsletter], <x-newsletter.form>)
    1. e-mail  → POST data-subscribe-url (envia o código por e-mail)
    2. código  → POST data-verify-url    (confirma; confete + mensagem de sucesso)
    A mensagem de retorno aparece abaixo do campo e do botão ([data-nl-feedback]).
    Dispara "newsletter:subscribed" (bubbles) no elemento raiz ao confirmar.
    init() é idempotente.
-------------------------------------------------*/
import * as ajaxModule from './ajax'
import { launchConfetti } from './confetti'

export const SUBSCRIBED_KEY = 'newsletter-subscribed'

export function init() {
    document.querySelectorAll('[data-newsletter]').forEach(bind)
}

function bind(root) {
    if (root.dataset.bound === 'true') return
    root.dataset.bound = 'true'

    const form = root.querySelector('form')
    const emailInput = root.querySelector('[data-nl-email]')
    const codeInput = root.querySelector('[data-nl-code]')
    const codeLabel = root.querySelector('[data-nl-code-label]')
    const submit = root.querySelector('[data-nl-submit]')
    const feedback = root.querySelector('[data-nl-feedback]')
    const actions = root.querySelector('[data-nl-actions]')
    const resend = root.querySelector('[data-nl-resend]')
    const change = root.querySelector('[data-nl-change]')

    let step = 'email' // email | code | done
    let email = ''
    let cooldownTimer = null

    form.addEventListener('submit', (event) => {
        event.preventDefault()
        if (step === 'email') requestCode()
        else if (step === 'code') confirmCode()
    })
    resend.addEventListener('click', requestCode)
    change.addEventListener('click', backToEmail)

    // ── Ações ────────────────────────────────────────────────

    async function requestCode() {
        const address = step === 'email' ? emailInput.value.trim() : email
        const data = new FormData(form)
        data.set('email', address)
        data.delete('code')

        setBusy(true)
        try {
            const response = await ajaxModule.init('POST', root.dataset.subscribeUrl, data)
            const body = await response.json()
            email = body.email ?? address
            showCodeStep(body)
        } catch (error) {
            const { message, retryAfter } = await readError(error)
            show('error', '', message)
            if (retryAfter) startCooldown(retryAfter)
        } finally {
            setBusy(false)
        }
    }

    async function confirmCode() {
        const data = new FormData(form)
        data.set('email', email)

        setBusy(true)
        try {
            await ajaxModule.init('POST', root.dataset.verifyUrl, data)
            celebrate()
        } catch (error) {
            const { message } = await readError(error)
            show('error', '', message)
            codeInput.select()
        } finally {
            setBusy(false)
        }
    }

    function celebrate() {
        step = 'done'
        // o campo volta a mostrar o e-mail inscrito, em vez do código digitado
        codeInput.hidden = true
        codeLabel.hidden = true
        emailInput.value = email
        emailInput.hidden = false
        actions.hidden = true
        stopCooldown()
        setLabel('Inscrito')
        show('success', 'Você está dentro!', 'Inscrição confirmada. O próximo post chega direto no seu e-mail.')
        launchConfetti({ colors: palette() })

        try { window.localStorage.setItem(SUBSCRIBED_KEY, '1') } catch (e) { /* modo privado: ignora */ }
        root.dispatchEvent(new CustomEvent('newsletter:subscribed', { bubbles: true }))
    }

    // ── Etapas ───────────────────────────────────────────────

    function showCodeStep(body) {
        step = 'code'
        emailInput.disabled = true
        emailInput.hidden = true
        codeInput.disabled = false
        codeInput.hidden = false
        codeLabel.hidden = false
        codeInput.value = ''
        setLabel('Confirmar')
        actions.hidden = false

        const minutes = body.expires_in_minutes ?? 15
        show('info', 'Código enviado', `Digite o código de 6 dígitos que enviamos para ${email}. Ele vale por ${minutes} minutos.`)
        startCooldown(body.retry_after ?? 60)
        codeInput.focus()
    }

    function backToEmail() {
        step = 'email'
        stopCooldown()
        emailInput.disabled = false
        emailInput.hidden = false
        codeInput.disabled = true
        codeInput.hidden = true
        codeLabel.hidden = true
        codeInput.value = ''
        feedback.hidden = true
        actions.hidden = true
        setLabel('Assinar')
        emailInput.focus()
    }

    // ── Apoio ────────────────────────────────────────────────

    function show(tone, title, text) {
        feedback.dataset.tone = tone
        feedback.querySelector('[data-nl-title]').textContent = title
        feedback.querySelector('[data-nl-text]').textContent = text
        feedback.hidden = false
        // reinicia a animação de entrada quando a mensagem é trocada
        feedback.style.animation = 'none'
        void feedback.offsetWidth
        feedback.style.animation = ''
    }

    function setLabel(text) {
        const label = submit.querySelector('[data-label]')
        if (label) label.textContent = text
    }

    // Mesmo estado de carregamento do botão usado em ajax-post.js / form-submit.js
    function setBusy(on) {
        const spinner = submit.querySelector('[data-spinner]')
        const label = submit.querySelector('[data-label]')
        submit.disabled = on || step === 'done'
        submit.dataset.state = on ? 'loading' : 'idle'
        spinner?.classList.toggle('opacity-0', !on)
        spinner?.classList.toggle('opacity-100', on)
        label?.classList.toggle('opacity-0', on)
        label?.classList.toggle('opacity-100', !on)
    }

    function startCooldown(seconds) {
        stopCooldown()
        let left = seconds
        const tick = () => {
            resend.disabled = left > 0
            resend.textContent = left > 0 ? `Reenviar código (${left}s)` : 'Reenviar código'
            if (left-- <= 0) stopCooldown(false)
        }
        tick()
        cooldownTimer = setInterval(tick, 1000)
    }

    function stopCooldown(reset = true) {
        clearInterval(cooldownTimer)
        cooldownTimer = null
        if (reset) {
            resend.disabled = false
            resend.textContent = 'Reenviar código'
        }
    }

    // Cores do confete: tema do post (--topic) e paleta da marca
    function palette() {
        const css = getComputedStyle(root)
        const get = (name) => css.getPropertyValue(name).trim()
        return [get('--topic'), get('--color-brand'), get('--color-brand-deep'), get('--color-accent'), '#F4B740']
            .filter(Boolean)
    }
}

async function readError(error) {
    if (!error.response) {
        return { message: 'Sem conexão. Verifique sua internet e tente de novo.' }
    }

    let body = {}
    try { body = await error.response.json() } catch (e) { /* corpo vazio */ }

    const status = error.response.status
    const fieldError = body.errors ? Object.values(body.errors).flat()[0] : null

    if (fieldError) return { message: fieldError }
    if (status === 429) {
        return body.retry_after
            ? { message: body.message, retryAfter: body.retry_after }
            : { message: 'Muitas tentativas. Aguarde um minuto e tente de novo.' }
    }
    if (status >= 500) return { message: 'Algo deu errado do nosso lado. Tente de novo em instantes.' }

    return { message: body.message || 'Não foi possível concluir. Tente de novo.' }
}
