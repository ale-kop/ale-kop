export function init() {
    document.addEventListener('submit', function (e) {
        const form = e.target.closest('form')
        if (!form) return

        // Loading state for submit button
        const submitButton = form.querySelector('button[type="submit"]')
        if (submitButton) {
            const spinner = submitButton.querySelector('[data-spinner]')
            const label = submitButton.querySelector('[data-label]')
            submitButton.setAttribute('disabled', 'disabled')
            submitButton.dataset.state = 'loading'
            if (spinner) spinner.classList.remove('absolute', 'opacity-0')
            if (label) label.classList.add('hidden')

        }
    }, { capture: true })
}

