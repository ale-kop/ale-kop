export function init() {

    let scrollTriggers = document.querySelectorAll('[data-js-scroll]')
    if (scrollTriggers[0]) {

        scrollTriggers.forEach((trigger) => {
            let data = trigger.dataset.jsScroll ? JSON.parse(trigger.dataset.jsScroll) : {}

            if (!data.targetId || !data.event || !data.qty || !data.direction) {
                return false
            }

            console.log('uu')

            let options = {
                once: data.once || false,
            }

            if (trigger.scrollEventAdded !== true) {
                console.log('aaa')
                trigger.addEventListener(data.event, (event) => {
                    fireScroll(data.targetId, data.direction, data.qty)
                }, options)
                trigger.scrollEventAdded = true
            }
        })
    }
}

export function fireScroll(targetId, direction, qty) {
    switch (direction) {
        case 'left':
            fireScrollLeft(targetId, qty)
            break
        case 'right':
            fireScrollRight(targetId, qty)
            break
    }
}

export function fireScrollLeft(targetId, qty) {
    let target = document.getElementById(targetId)
    if (target) {
        qty = (qty === 'full') ? target.offsetWidth : parseInt(qty)
        target.scrollLeft -= qty
    } else {
        console.log(targetId + ' not found')
    }
}

export function fireScrollRight(targetId, qty) {
    let target = document.getElementById(targetId)
    if (target) {
        qty = (qty === 'full') ? target.offsetWidth : parseInt(qty)
        target.scrollLeft += qty
    } else {
        console.log(targetId + ' not found')
    }
}

/* export function hideIfReachedEnd(trigger,targetId, direction) {
    let scrollArea = document.getElementById(targetId)

    if (direction === 'right'){
        if ((Math.abs(scrollArea.scrollLeft) === scrollArea.scrollWidth - scrollArea.clientWidth)){
            trigger.classList.add('hidden')
            return true
        }
    }

    if (direction === 'left'){
        if ((Math.abs(scrollArea.scrollLeft) === 0)){
            trigger.classList.add('hidden')
            return true
        }
    }

    return false
} */
