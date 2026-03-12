export function init() {
    document.addEventListener('submit', function (e) {
        const form = e.target.closest('form')
        if (!form) return

        // Loading state for the actual submitter button (fallback to first submit)
        const submitButton = e.submitter || form.querySelector('button[type="submit"]')
        if (submitButton) {
            const spinner = submitButton.querySelector('[data-spinner]')
            const label = submitButton.querySelector('[data-label]')
            submitButton.setAttribute('disabled', 'disabled')
            submitButton.dataset.state = 'loading'
            // Keep spinner absolutely positioned and just reveal it
            if (spinner) {
                spinner.classList.remove('opacity-0')
                // Ensure we don't accidentally remove absolute positioning elsewhere
                spinner.classList.add('absolute')
            }
            // Keep label in flow to preserve width; hide visually only
            if (label) {
                label.classList.remove('hidden')
                label.classList.remove('opacity-100')
                label.classList.add('opacity-0')
            }

        }
    }, { capture: true })
}
