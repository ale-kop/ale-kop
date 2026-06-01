import * as ajaxModule from './ajax'

export default window.Modal = {
    loadFromURLAndOpen(modalId, url, closeOnEsc = true) {

        if (modalId && url) {

            let modal = document.getElementById(modalId) || null

            if (modal === null) {
                return false
            }

            Modal.open(modal.id)

            let ajaxObj = ajaxModule.init('GET', url)

            ajaxObj.onload = function () {
                let data = ajaxObj.response

                if (ajaxObj.status === 200) {
                    modal.querySelector('[data-loading]').classList.add('hidden')
                    modal.querySelector('[data-content]').innerHTML = data.content
                    ajaxModule.includeScripts(modal)
                    initAllModules()
                    Modal.addCloseEventListenerToHeaderButton(modal)
                } else {
                    Modal.loadAlert({
                        'title': 'Não encontrado', 'type': 'warning',
                    })
                    Modal.close(modal.id)
                }

                modal.scrollTop = 0
            }

            ajaxObj.send()
        }
    },

    loadAlert(params) {

        if (params.content || params.title) {

            let modal = document.getElementById('alert-modal') || null

            if (modal === null) {
                return false
            }

            modal.classList.remove('transition-all','duration-300','-translate-y-[30px]', 'opacity-0');

            if (params.title === '' || params.title === undefined) {
                modal.querySelector('[data-title]').classList.add('hidden')
            } else {
                modal.querySelector('[data-title]').classList.remove('hidden')
                modal.querySelector('[data-title]').innerHTML = params.title
            }

            if (params.content === '' || params.content === undefined) {
                modal.querySelector('[data-content]').classList.add('hidden')
            } else {
                modal.querySelector('[data-content]').classList.remove('hidden')
                modal.querySelector('[data-content]').innerHTML = params.content
            }

            let possibleTypes = ['success', 'error', 'warning', 'info']

            possibleTypes.forEach(typeValue => {
                if (typeValue !== params.type) {
                    modal.querySelector('[data-icon-' + typeValue + ']').classList.add('hidden')
                } else {
                    modal.querySelector('[data-icon-' + typeValue + ']').classList.remove('hidden')
                }
            })

            Modal.open(modal.id)

            if (params.timerToClose > 0) {
                /*let timeoutBar = modal.querySelector('[data-timeout-bar]')
                let originalWidth = timeoutBar.clientWidth
                modal.addEventListener('mouseover', () => {
                    timeoutBar.style.width = originalWidth+'px'
                });*/
                Modal.resumeTimer(modal, timerToClose)
            }

            if (params.onClose) {
                modal.addEventListener('close', params.onClose)
            }

        }
    },

    resumeTimer(modal, timerToClose) {
        let timeoutBar = modal.querySelector('[data-timeout-bar]')
        timeoutBar.classList.remove('hidden')
        let originalWidth             = timeoutBar.clientWidth
        let totalTimeInSeconds        = timerToClose * 1000
        let widthToSubtractEachSecond = timeoutBar.clientWidth / (totalTimeInSeconds / 100)

        let timer = setInterval(() => {
            if (totalTimeInSeconds === 0) {
                clearInterval(timer)
                Modal.close(modal.id)
                timeoutBar.style.width = originalWidth + 'px'
                timeoutBar.classList.add('hidden')
            }
            totalTimeInSeconds     = totalTimeInSeconds - 100
            timeoutBar.style.width = (timeoutBar.clientWidth - widthToSubtractEachSecond) + 'px'
        }, 150)
    },

    open(modalId, closeOnEsc = true, closeOnBackdropClick = false) {
        let modal = document.getElementById(modalId) || null

        if (modal === null) return false

        modal.showModal()

        document.body.classList.add('overflow-y-hidden')

        // Esc key pressed listener
        modal.addEventListener('cancel', (event) => {
            if (closeOnEsc === false) {
                event.preventDefault()
            } else {
                Modal.close(modal.id)
            }
        })

        if (closeOnBackdropClick) {
            modal.addEventListener('click', (event) => {
                if (event.target.nodeName === 'DIALOG')
                    Modal.close(modal.id)
            })
        }

    },

    close(modalId) {
        let modal = document.getElementById(modalId) || null

        if (modal === null) return false

        modal.classList.add('transition-all','duration-150','-translate-y-[30px]', 'opacity-0');

        setTimeout(() => {
            if (modal.querySelector('[data-content]'))
                modal.querySelector('[data-content]').innerHTML = ''

            if (modal.querySelector('[data-loading]'))
                modal.querySelector('[data-loading]').classList.remove('hidden')

            modal.close();
            document.body.classList.remove('overflow-y-hidden')
        }, 200);

    },

    addCloseEventListenerToHeaderButton(modal) {
        modal.querySelectorAll('[data-close]').forEach((element) => {
            element.addEventListener('click', () => {
                Modal.close(modal.id)
            })
        })
    },
}
