import {EditorState} from 'prosemirror-state'
import {EditorView} from 'prosemirror-view'
import {Schema, DOMParser as ProseParser, DOMSerializer} from 'prosemirror-model'
import {schema as basicSchema} from 'prosemirror-schema-basic'
import {addListNodes, wrapInList, splitListItem} from 'prosemirror-schema-list'
import {history, undo, redo} from 'prosemirror-history'
import {keymap} from 'prosemirror-keymap'
import {Plugin} from 'prosemirror-state'
import {baseKeymap, toggleMark, setBlockType, wrapIn, chainCommands, exitCode} from 'prosemirror-commands'
import {inputRules, textblockTypeInputRule, wrappingInputRule, InputRule, smartQuotes, ellipsis, emDash} from 'prosemirror-inputrules'

const underline = {
    parseDOM: [{tag: 'u'}, {style: 'text-decoration=underline'}],
    toDOM() {
        return ['u', 0]
    },
}

const strike = {
    parseDOM: [{tag: 's'}, {tag: 'del'}, {style: 'text-decoration=line-through'}],
    toDOM() {
        return ['s', 0]
    },
}

export function init() {
    document.querySelectorAll('[data-rich-text-editor]').forEach(container => {
        if (container.editor) return
        const targetId = container.id
        const hidden = document.querySelector(`[data-editor-target="${targetId}"]`)
        const initialEl = document.querySelector(`[data-editor-initial="${targetId}"]`)
        const initial = initialEl ? (initialEl.value || initialEl.innerHTML || initialEl.textContent) : ''
        const value = initial || (hidden ? hidden.value : '')

        const nodeSpecs = addListNodes(basicSchema.spec.nodes, 'paragraph block*', 'block')
            .update('paragraph', {
                content: 'inline*',
                group: 'block',
                attrs: {align: {default: 'left'}},
                parseDOM: [{tag: 'p', getAttrs: dom => ({align: dom.style.textAlign || 'left'})}],
                toDOM(node) { return ['p', {style: `text-align:${node.attrs.align}`}, 0] }
            })
            .update('heading', {
                content: 'inline*',
                group: 'block',
                defining: true,
                attrs: {level: {default: 1}, align: {default: 'left'}},
                parseDOM: [
                    {tag: 'h1', getAttrs: dom => ({level:1, align: dom.style.textAlign || 'left'})},
                    {tag: 'h2', getAttrs: dom => ({level:2, align: dom.style.textAlign || 'left'})},
                    {tag: 'h3', getAttrs: dom => ({level:3, align: dom.style.textAlign || 'left'})},
                    {tag: 'h4', getAttrs: dom => ({level:4, align: dom.style.textAlign || 'left'})},
                    {tag: 'h5', getAttrs: dom => ({level:5, align: dom.style.textAlign || 'left'})},
                    {tag: 'h6', getAttrs: dom => ({level:6, align: dom.style.textAlign || 'left'})},
                ],
                toDOM(node) { return ['h'+node.attrs.level, {style: `text-align:${node.attrs.align}`}, 0] }
            })

        const marks = basicSchema.spec.marks
            .addBefore('link', 'underline', underline)
            .addToEnd('strike', strike)

        const schema = new Schema({nodes: nodeSpecs, marks})

        const parser = new DOMParser()
        const content = parser.parseFromString(value || '<p></p>', 'text/html')
        const state = EditorState.create({
            doc: ProseParser.fromSchema(schema).parse(content.body),
            plugins: [
                history(),
                inputRules({rules: buildInputRules(schema)}),
                trailingParagraphPlugin(schema.nodes.paragraph),
                keymap({
                    'Enter': splitListItem(schema.nodes.list_item),
                    'Shift-Enter': chainCommands(
                        exitCode,
                        (state, dispatch) => {
                            dispatch(
                                state.tr.replaceSelectionWith(
                                    state.schema.nodes.hard_break.create()
                                ).scrollIntoView()
                            )
                            return true
                        }
                    ),
                    'Mod-b': toggleMark(schema.marks.strong),
                    'Mod-i': toggleMark(schema.marks.em),
                    'Mod-u': toggleMark(schema.marks.underline),
                    'Mod-Shift-s': toggleMark(schema.marks.strike),
                    'Mod-0': setBlockType(schema.nodes.paragraph),
                    'Mod-z': undo,
                    'Mod-y': redo,
                    'Shift-Mod-z': redo
                }),
                keymap(baseKeymap)
            ]
        })

        const view = new EditorView(container.querySelector('.editor'), {
            state,
            dispatchTransaction(tr){
                const newState = view.state.apply(tr)
                view.updateState(newState)
                if(hidden){
                    hidden.value = getHTML(view.state.doc, schema)
                }
                updateToolbar(container, schema, view)
            }
        })

        setupToolbar(container, schema, view)
        if(hidden){
            hidden.value = getHTML(view.state.doc, schema)
        }
        updateToolbar(container, schema, view)
        container.editor = view
    })

}

function setupToolbar(container, schema, view) {
    container.querySelectorAll('[data-command]').forEach(btn => {
        const cmd = btn.getAttribute('data-command')
        if (cmd === 'heading') {
            btn.addEventListener('change', e => {
                const level = Number(e.target.value)
                if (level === 0) {
                    setBlockType(schema.nodes.paragraph)(view.state, view.dispatch)
                } else {
                    setBlockType(schema.nodes.heading, {level})(view.state, view.dispatch)
                }
                view.focus()
            })
        } else if (cmd === 'bullet') {
            btn.addEventListener('click', () => {
                wrapInList(schema.nodes.bullet_list)(view.state, view.dispatch)
                view.focus()
            })
        } else if (cmd === 'ordered') {
            btn.addEventListener('click', () => {
                wrapInList(schema.nodes.ordered_list)(view.state, view.dispatch)
                view.focus()
            })
        } else if (cmd === 'blockquote') {
            btn.addEventListener('click', () => {
                wrapIn(schema.nodes.blockquote)(view.state, view.dispatch)
                view.focus()
            })
        } else if (cmd === 'code') {
            btn.addEventListener('click', () => {
                setBlockType(schema.nodes.code_block)(view.state, view.dispatch)
                view.focus()
            })
        } else if (cmd === 'bold') {
            btn.addEventListener('click', () => {
                toggleMark(schema.marks.strong)(view.state, view.dispatch)
                view.focus()
            })
        } else if (cmd === 'italic') {
            btn.addEventListener('click', () => {
                toggleMark(schema.marks.em)(view.state, view.dispatch)
                view.focus()
            })
        } else if (cmd === 'strike') {
            btn.addEventListener('click', () => {
                toggleMark(schema.marks.strike)(view.state, view.dispatch)
                view.focus()
            })
        } else if (cmd === 'underline') {
            btn.addEventListener('click', () => {
                toggleMark(schema.marks.underline)(view.state, view.dispatch)
                view.focus()
            })
        } else if (cmd === 'link') {
            btn.addEventListener('click', () => {
                const url = prompt('URL')
                if (url) {
                    toggleMark(schema.marks.link, {href: url})(view.state, view.dispatch)
                    view.focus()
                }
            })
        } else if (cmd === 'align-left' || cmd === 'align-center' || cmd === 'align-right') {
            btn.addEventListener('click', () => {
                const align = cmd.split('-')[1]
                setBlockType(getCurrentBlockType(view, schema), {align})(view.state, view.dispatch)
                view.focus()
            })
        } else if (cmd === 'undo') {
            btn.addEventListener('click', () => {
                undo(view.state, view.dispatch)
                view.focus()
            })
        } else if (cmd === 'redo') {
            btn.addEventListener('click', () => {
                redo(view.state, view.dispatch)
                view.focus()
            })
        }
    })
}

function getCurrentBlockType(view, schema) {
    const {$from} = view.state.selection
    if ($from.parent.type === schema.nodes.heading) return schema.nodes.heading
    if ($from.parent.type === schema.nodes.code_block) return schema.nodes.code_block
    if ($from.parent.type === schema.nodes.blockquote) return schema.nodes.blockquote
    return schema.nodes.paragraph
}

function getHTML(doc, schema) {
    const div = document.createElement('div')
    div.appendChild(DOMSerializer.fromSchema(schema).serializeFragment(doc.content))
    div.querySelectorAll('br.ProseMirror-trailingBreak').forEach(br => br.remove())
    div.querySelectorAll('li').forEach(li => {
        const p = li.querySelector(':scope > p')
        if(p && li.children.length === 1){
            while(p.firstChild) li.insertBefore(p.firstChild, p)
            li.removeChild(p)
        }
        if(li.textContent.trim() === '' && li.querySelector('br')){
            li.remove()
        }
    })
    return div.innerHTML
}

function markInputRule(regexp, markType){
    return new InputRule(regexp, (state, match, start, end) => {
        const text = match[1]
        if(!text) return null
        const tr = state.tr
        tr.insertText(text, start, end)
        tr.addMark(start, start + text.length, markType.create())
        tr.removeStoredMark(markType)
        return tr
    })
}

function buildInputRules(schema){
    const rules = smartQuotes.concat(ellipsis, emDash)
    let type
    if(type = schema.nodes.blockquote) rules.push(wrappingInputRule(/^\s*>\s$/, type))
    if(type = schema.nodes.ordered_list) rules.push(wrappingInputRule(/^(\d+)\.\s$/, type, match => ({order: +match[1]}), (match, node) => node.childCount + node.attrs.order == +match[1]))
    if(type = schema.nodes.bullet_list) rules.push(wrappingInputRule(/^\s*([-+*])\s$/, type))
    if(type = schema.nodes.code_block) rules.push(textblockTypeInputRule(/^```$/, type))
    if(type = schema.nodes.heading) rules.push(textblockTypeInputRule(/^(#{1,6})\s$/, type, match => ({level: match[1].length})))
    if(type = schema.marks.strong) rules.push(markInputRule(/\*\*([^*]+)\*\*$/, type))
    if(type = schema.marks.em) rules.push(markInputRule(/\*([^*]+)\*$/, type))
    if(type = schema.marks.underline) rules.push(markInputRule(/__([^_]+)__$/, type))
    if(type = schema.marks.strike) rules.push(markInputRule(/~~([^~]+)~~$/, type))
    return rules
}

function trailingParagraphPlugin(nodeType){
    return new Plugin({
        appendTransaction(transactions, oldState, newState){
            if(!transactions.some(tr => tr.docChanged)) return null
            const last = newState.doc.lastChild
            if(!last || last.type !== nodeType){
                return newState.tr.insert(newState.doc.content.size, nodeType.create())
            }
        }
    })
}

function updateToolbar(container, schema, view){
    const state = view.state
    const {from} = state.selection
    const marks = state.storedMarks || state.doc.resolve(from).marks()
    const markActive = type => marks.some(m => m.type === type)

    const boldBtn = container.querySelector('[data-command="bold"]')
    const italicBtn = container.querySelector('[data-command="italic"]')
    const underlineBtn = container.querySelector('[data-command="underline"]')
    const strikeBtn = container.querySelector('[data-command="strike"]')

    boldBtn.classList.toggle('active', markActive(schema.marks.strong))
    italicBtn.classList.toggle('active', markActive(schema.marks.em))
    underlineBtn.classList.toggle('active', markActive(schema.marks.underline))
    strikeBtn.classList.toggle('active', markActive(schema.marks.strike))

    const select = container.querySelector('[data-command="heading"]')
    const block = state.selection.$from.parent
    if(block.type === schema.nodes.heading){
        select.value = String(block.attrs.level)
    }else{
        select.value = '0'
    }
}
