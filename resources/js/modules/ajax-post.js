import * as ajaxModule from './ajax'
import {updateSlots} from './ajax-slot'

document.addEventListener('DOMContentLoaded', () => {
    document.body.addEventListener('click', function (e) {
        const ajaxElement = e.target.closest('[ak-ajax-click]')
        if (!ajaxElement) return

        postData(e, ajaxElement)
    })
})

export async function postData(e, ajaxElement) {
    const button    = ajaxElement
    const formId    = button.getAttribute('ak-ajax-click')
    const form      = document.getElementById(formId)
    let action      = button.getAttribute('ak-ajax-action')
    let loadingText = button.getAttribute('ak-ajax-loading-text') || 'Carregando...'
    const formData  = new FormData(form)

    try {
        // Show loading icon and disable button
        setButtonLoadingState(button, true)

        // Make the request using the ajaxModule
        const response = await ajaxModule.init('POST', action, formData)

        // Handle the response
        await handleAjaxResponse(response, button)

    } catch (error) {
        let data = {message: 'An unexpected error occurred', type: 'warning'}

        let errorBody = {}
        if (error.response) {
            try {
                errorBody = await error.response.json()
            } catch (_) {
                errorBody = {}
            }

            const status = error.response.status

            if (status === 400) {
                data.message = errorBody.message ?? 'Requisição inválida.'
            } else if (status === 422) {
                data.message = errorBody.message ?? 'Erro no servidor.'
            } else if (status === 403) {
                data.message = errorBody.message ?? 'Proibido. Você não possui acesso a este recurso.'
            } else if (status === 404) {
                data.message = errorBody.message ?? 'Recurso não encontrado.'
            } else if (status === 500) {
                data.message = errorBody.message ?? 'Erro interno no servidor.'
            } else {
                data.message = errorBody.message ?? `Erro inesperado: ${status}`
            }
        } else {
            // Handle errors that are not HTTP errors, such as network issues
            console.error('Network error or unexpected issue:', error)
            data.message = 'Erro de rede/conexão. Verifique sua conexão.'
        }

        console.error('Error during fetch:', error)
        showWarning(data)

        // Execute JavaScript code if provided, even on error
        if (errorBody.js) {
            try {
                eval(errorBody.js)
            } catch (jsError) {
                console.error('Error executing server-provided JS (from error response):', jsError)
            }
        }
    } finally {
        // Hide loading icon and enable button
        setButtonLoadingState(button, false)
    }

    e.stopPropagation()
}

async function handleAjaxResponse(response, button) {
    let data
    try {
        data = await response.json() // Parse JSON from the response
    } catch (error) {
        console.dir('Error parsing JSON:', error)
        showWarning({message: 'Error parsing server response'})
        return
    }

    showSuccessAlert(data)
    updateSlots(data)

    // Execute JavaScript code if provided
    if (data.js) {
        try {
            eval(data.js) // Execute JavaScript code received from the server
        } catch (error) {
            console.error('Error executing server-provided JS:', error)
        }
    }

    if (data.redirect) {
        window.location.replace(data.redirect)
    }

    if (data.modalIdToClose) {
        Modal.close(data.modalIdToClose)
    }
}

function setButtonLoadingState(button, isLoading) {
    const loadingIcon  = button.querySelector('.loading-icon')
    const originalIcon = button.querySelector('.original-icon')
    // Support new data-* API used by <x-forms.button>
    const spinnerEl = button.querySelector('[data-spinner]')
    const labelEl   = button.querySelector('[data-label]')

    if (loadingIcon) {
        loadingIcon.classList.toggle('opacity-0', !isLoading)
        loadingIcon.classList.toggle('absolute', !isLoading)
    }

    if (originalIcon) {
        originalIcon.classList.toggle('opacity-0', isLoading)
        originalIcon.classList.toggle('absolute', isLoading)
    }

    if (spinnerEl || labelEl) {
        if (isLoading) {
            if (spinnerEl) {
                spinnerEl.classList.remove('opacity-0')
                spinnerEl.classList.add('absolute')
            }
            if (labelEl) {
                labelEl.classList.remove('hidden')
                labelEl.classList.remove('opacity-100')
                labelEl.classList.add('opacity-0')
            }
        } else {
            if (spinnerEl) {
                spinnerEl.classList.add('opacity-0')
                // keep absolute to center overlay when hidden; harmless
                spinnerEl.classList.add('absolute')
            }
            if (labelEl) {
                labelEl.classList.remove('opacity-0')
                labelEl.classList.add('opacity-100')
            }
        }
    }

    if (isLoading) {
        button.setAttribute('disabled', 'disabled')
        button.classList.add('cursor-progress')
        button.classList.remove('active:shadow-inner')
    } else {
        button.removeAttribute('disabled')
        button.classList.remove('cursor-progress')
        button.classList.add('active:shadow-inner')
    }
}


// Alerts and error handling
export function showValidationAlert(data) {
    Modal.loadAlert(data)
}

export function showWarning(data) {
    Modal.loadAlert({title: 'Atenção', content: data.message, type: 'warning'})
}

export function showSuccessAlert(data) {
    if (data.reload === 1) {
        Modal.loadAlert({
            content: data.message,
            title  : data.title || 'Alerta',
            type   : data.type || 'success',
            onClose: () => {
                if (data.goToURL) window.location.replace(data.goToURL)
                else window.location.reload()
            },
        })
        return
    }
    console.dir(data)
    Toast.open({content: data.message, title: data.title || 'Alerta', type: data.type || 'success'})
}
