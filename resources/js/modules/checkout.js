/*------------------------------------------------
    Checkout transparente do Mercado Pago
    [data-checkout]  formulário (resources/views/checkout/show.blade.php)
      - Cartão: Secure Fields do MercadoPago.js. Número, validade e CVV ficam em iframes
        do Mercado Pago; o navegador gera um token e só ele vai para o servidor.
      - Pix: o servidor cria o pagamento e a página do pedido mostra o QR Code.
    [data-pix]       Pix aguardando pagamento (resources/views/checkout/order.blade.php):
                     copia o código, conta o tempo e consulta o status até aprovar.
    init() é idempotente.
-------------------------------------------------*/
const SDK_URL = 'https://sdk.mercadopago.com/js/v2'
const POLL_MS = 4000

export function init() {
    document.querySelectorAll('[data-checkout]').forEach(bindCheckout)
    document.querySelectorAll('[data-pix]').forEach(bindPix)
}

// ── Formulário ───────────────────────────────────────────────

function bindCheckout(form) {
    if (form.dataset.bound === 'true') return
    form.dataset.bound = 'true'

    const submit = form.querySelector('[data-checkout-submit]')
    const errorBox = form.querySelector('[data-checkout-error]')
    const installmentsSelect = form.querySelector('[data-installments]')
    const brandBadge = form.querySelector('[data-card-brand]')
    const cpfInput = form.querySelector('[data-checkout-cpf]')
    const amount = form.dataset.amount
    const maxInstallments = Number(form.dataset.maxInstallments || 12)

    cpfInput.addEventListener('input', () => { cpfInput.value = formatCpf(cpfInput.value) })

    let card = null // { mp, fields, paymentMethod, issuerId }
    let cardReady = null

    const method = () => form.querySelector('[name="payment_method"]:checked').value

    form.querySelectorAll('[name="payment_method"]').forEach((radio) => {
        radio.addEventListener('change', showMethod)
    })
    showMethod()

    form.addEventListener('submit', async (event) => {
        event.preventDefault()
        clearError()

        const missing = ['email', 'name', 'document'].find((name) => !form.elements[name].value.trim())
        if (missing) {
            fail(fieldMessage(missing), form.elements[missing])
            return
        }

        setBusy(true)
        try {
            const data = new FormData(form)
            if (method() === 'card') {
                await cardReady
                await addCardData(data)
            }
            await pay(data)
        } catch (error) {
            fail(error.message || 'Não foi possível concluir o pagamento. Tente de novo.', error.field)
            setBusy(false)
        }
    })

    function showMethod() {
        const current = method()
        form.querySelectorAll('[data-panel]').forEach((panel) => {
            panel.classList.toggle('hidden', panel.dataset.panel !== current)
        })
        submit.textContent = current === 'pix' ? submit.dataset.labelPix : submit.dataset.labelCard
        clearError()

        if (current === 'card' && !cardReady) {
            cardReady = setupCard().catch(() => {
                fail('O pagamento com cartão está indisponível agora. Use o Pix ou tente de novo em instantes.')
            })
        }
    }

    // ── Cartão ───────────────────────────────────────────────

    async function setupCard() {
        if (!form.dataset.publicKey) throw new Error('missing public key')

        await loadSdk()
        const mp = new window.MercadoPago(form.dataset.publicKey, { locale: 'pt-BR' })
        const style = { height: '100%', fontSize: '16px', fontFamily: 'system-ui, sans-serif', color: '#1F2433' }

        const fields = {
            cardNumber: mp.fields.create('cardNumber', { placeholder: '0000 0000 0000 0000', style }).mount('mp-card-number'),
            expirationDate: mp.fields.create('expirationDate', { placeholder: 'MM/AA', style }).mount('mp-expiration'),
            securityCode: mp.fields.create('securityCode', { placeholder: 'CVV', style }).mount('mp-security-code'),
        }

        card = { mp, fields, paymentMethod: null, issuerId: null, bin: null }

        fields.cardNumber.on('binChange', async ({ bin }) => {
            if (!bin) {
                resetCard()
                return
            }
            if (bin === card.bin) return
            card.bin = bin

            try {
                const { results } = await mp.getPaymentMethods({ bin })
                const paymentMethod = results?.[0]
                if (!paymentMethod) return

                card.paymentMethod = paymentMethod
                brandBadge.textContent = paymentMethod.name || paymentMethod.id
                brandBadge.classList.replace('hidden', 'flex')

                const settings = paymentMethod.settings?.[0]
                if (settings) {
                    fields.cardNumber.update({ settings: settings.card_number })
                    fields.securityCode.update({ settings: settings.security_code })
                }

                card.issuerId = paymentMethod.issuer?.id ?? null
                if (paymentMethod.additional_info_needed?.includes('issuer_id')) {
                    const issuers = await mp.getIssuers({ paymentMethodId: paymentMethod.id, bin })
                    card.issuerId = issuers?.[0]?.id ?? card.issuerId
                }

                await loadInstallments(bin)
            } catch {
                fail('Não conseguimos verificar este cartão. Confira o número.')
            }
        })
    }

    async function loadInstallments(bin) {
        const response = await card.mp.getInstallments({ amount, bin, paymentTypeId: 'credit_card' })
        const costs = (response?.[0]?.payer_costs || []).filter((cost) => cost.installments <= maxInstallments)

        installmentsSelect.replaceChildren(...costs.map((cost) => {
            const option = document.createElement('option')
            option.value = cost.installments
            option.textContent = cost.recommended_message
            return option
        }))
        installmentsSelect.disabled = costs.length === 0
    }

    function resetCard() {
        if (!card) return
        card.bin = null
        card.paymentMethod = null
        card.issuerId = null
        brandBadge.classList.replace('flex', 'hidden')
        installmentsSelect.replaceChildren(new Option('Informe o número do cartão', '1'))
        installmentsSelect.disabled = true
    }

    async function addCardData(data) {
        if (!card) throw new Error('O pagamento com cartão está indisponível agora. Use o Pix ou tente de novo em instantes.')
        if (!card.paymentMethod) throw new Error('Confira o número do cartão.')

        const holder = form.querySelector('[data-cardholder]')
        if (!holder.value.trim()) throw Object.assign(new Error('Informe o nome impresso no cartão.'), { field: holder })

        let token
        try {
            token = await card.mp.fields.createCardToken({
                cardholderName: holder.value.trim(),
                identificationType: 'CPF',
                identificationNumber: cpfInput.value.replace(/\D/g, ''),
            })
        } catch {
            throw new Error('Confira o número, a validade e o código de segurança do cartão.')
        }

        data.set('token', token.id)
        data.set('payment_method_id', card.paymentMethod.id)
        if (card.issuerId) data.set('issuer_id', card.issuerId)
        data.set('installments', installmentsSelect.value || '1')
    }

    // ── Envio ────────────────────────────────────────────────

    async function pay(data) {
        const response = await fetch(form.dataset.payUrl, {
            method: 'POST',
            headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken() },
            body: data,
        })
        const body = await response.json().catch(() => ({}))

        if (!response.ok) {
            const field = body.errors ? form.elements[Object.keys(body.errors)[0]] : null
            throw Object.assign(new Error(body.message || 'Não foi possível concluir o pagamento. Tente de novo.'), { field })
        }

        window.location.assign(body.redirect)
    }

    // ── Estado da tela ───────────────────────────────────────

    function setBusy(busy) {
        submit.disabled = busy
        if (busy) {
            submit.dataset.previous = submit.textContent
            submit.textContent = method() === 'pix' ? 'Gerando o Pix…' : 'Processando o pagamento…'
        } else if (submit.dataset.previous) {
            submit.textContent = submit.dataset.previous
        }
    }

    function fail(message, field = null) {
        errorBox.textContent = message
        errorBox.classList.remove('hidden')
        if (field instanceof HTMLElement) {
            field.setAttribute('aria-invalid', 'true')
            field.focus()
            field.addEventListener('input', () => field.removeAttribute('aria-invalid'), { once: true })
        }
    }

    function clearError() {
        errorBox.classList.add('hidden')
        errorBox.textContent = ''
    }
}

function fieldMessage(name) {
    return {
        email: 'Informe seu e-mail.',
        name: 'Informe seu nome completo.',
        document: 'Informe seu CPF.',
    }[name]
}

// ── Pix aguardando pagamento ─────────────────────────────────

function bindPix(root) {
    if (root.dataset.bound === 'true') return
    root.dataset.bound = 'true'

    const copyButton = root.querySelector('[data-pix-copy]')
    const codeInput = root.querySelector('[data-pix-code]')
    const statusText = root.querySelector('[data-pix-status]')
    const countdown = root.querySelector('[data-pix-countdown]')
    const expiresAt = root.dataset.expiresAt ? new Date(root.dataset.expiresAt) : null

    copyButton?.addEventListener('click', async () => {
        try {
            await navigator.clipboard.writeText(codeInput.value)
        } catch {
            codeInput.select()
            document.execCommand('copy')
        }
        copyButton.textContent = 'Copiado'
        setTimeout(() => { copyButton.textContent = 'Copiar' }, 2000)
    })

    const tick = () => {
        if (!expiresAt || !countdown) return
        const seconds = Math.max(0, Math.round((expiresAt - Date.now()) / 1000))
        countdown.textContent = `${Math.floor(seconds / 60)}:${String(seconds % 60).padStart(2, '0')}`
    }
    tick()
    const clock = setInterval(tick, 1000)

    const poll = async () => {
        try {
            const response = await fetch(root.dataset.statusUrl, { headers: { Accept: 'application/json' } })
            if (response.ok) {
                const { status, redirect } = await response.json()
                if (status !== 'pending') {
                    clearInterval(clock)
                    statusText.textContent = status === 'approved' ? 'Pagamento confirmado. Abrindo seu pedido…' : 'Atualizando…'
                    window.location.assign(redirect)
                    return
                }
            }
        } catch {
            // rede instável: tenta de novo no próximo ciclo
        }

        if (expiresAt && Date.now() > expiresAt.getTime() + 60_000) {
            window.location.reload()
            return
        }
        setTimeout(poll, POLL_MS)
    }
    setTimeout(poll, POLL_MS)
}

// ── Utilidades ───────────────────────────────────────────────

let sdkPromise = null

function loadSdk() {
    if (window.MercadoPago) return Promise.resolve()
    sdkPromise ??= new Promise((resolve, reject) => {
        const script = document.createElement('script')
        script.src = SDK_URL
        script.onload = resolve
        script.onerror = () => {
            sdkPromise = null
            reject(new Error('SDK do Mercado Pago não carregou'))
        }
        document.head.appendChild(script)
    })
    return sdkPromise
}

function formatCpf(value) {
    const d = value.replace(/\D/g, '').slice(0, 11)
    return d
        .replace(/^(\d{3})(\d)/, '$1.$2')
        .replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3')
        .replace(/\.(\d{3})(\d{1,2})$/, '.$1-$2')
}

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
}
