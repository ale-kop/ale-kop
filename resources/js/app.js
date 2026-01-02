import * as toggle from './modules/toggle'
import * as modal from './modules/modal'
import * as toast from './modules/toast'
import * as ajaxPost from './modules/ajax-post.js'
import * as richEditor from './modules/rich-text-editor.js'
import * as themeSwitch from './modules/theme-switch.js'
import * as formSubmit from './modules/form-submit.js'
import * as codeCopy from './modules/code-copy.js'
import * as postIndex from './modules/post-index.js'
import * as sidePanel from './modules/side-panel.js'
import * as topNav from './modules/top-nav.js'

import.meta.glob([
    '../img/**',
    '../fonts/**',
])

window.globalModules = {
    "toggle"    : toggle,
    "richEditor": richEditor,
    "themeSwitch": themeSwitch,
    "formSubmit": formSubmit,
    "codeCopy": codeCopy,
    "postIndex": postIndex,
    "sidePanel": sidePanel,
    "topNav": topNav,
}

/*------------------------------------------------
    Triggers after document load
-------------------------------------------------*/
document.addEventListener('DOMContentLoaded', () => {
    initAllModules()

    if (document.keyboardShortcutEventAdded !== true) {
        let ignoreKeys = ['ArrowUp', 'ArrowRight', 'ArrowDown', 'ArrowLeft', 'Alt', 'Shift', 'Meta']
        document.addEventListener('keydown', (event) => {
            if (ignoreKeys.indexOf(event.key) <= -1 && event.ctrlKey && event.key === 'b') {
                // some action / click / etc
            }

            if (ignoreKeys.indexOf(event.key) <= -1 && event.key === 'Escape') {
                // some action / click / etc
                // Example: Modal.close('main-modal')
            }
        }, {once: false})
        document.keyboardShortcutEventAdded = true
    }
})

/*------------------------------------------------
    Make the initAllModules method global
-------------------------------------------------*/
window.initAllModules = () => {
    Object.entries(globalModules).forEach(([moduleName, module]) => {
        module.init()
    })
}

/*------------------------------------------------
    Init only specific modules
-------------------------------------------------*/
window.initListOfModules = (listOfModulesToInit) => {
    listOfModulesToInit.forEach((module) => {
        globalModules[module].init()
    })
}
