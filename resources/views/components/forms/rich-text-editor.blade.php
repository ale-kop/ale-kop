@props(['name', 'id' => null, 'value' => ''])
@php use Illuminate\Support\Str; @endphp
@php
    $id = $id ?? Str::uuid()->toString();
@endphp
@php
    // Prefer slot content when provided; fallback to `value` prop
    $initial = trim($slot) !== '' ? $slot : $value;
@endphp

<div id="{{ $id }}" data-rich-text-editor class="bg-white border border-gray-300 rounded-md">
    <div class="toolbar flex flex-wrap text-gray-600 bg-gray-50 gap-1.5 p-2">
        <select data-command="heading" class="toolbar-item text-base">
            <option value="0">Parágrafo</option>
            <option value="1">H1</option>
            <option value="2">H2</option>
            <option value="3">H3</option>
        </select>
        <button type="button" data-command="bold" class="toolbar-item">
            <x-heroicon-s-bold class="w-5 h-5 text-gray-600" />
        </button>
        <button type="button" data-command="italic" class="toolbar-item">
            <x-heroicon-s-italic class="w-5 h-5 text-gray-600" />
        </button>
        <button type="button" data-command="strike" class="toolbar-item line-through">
            <x-heroicon-s-strikethrough class="w-5 h-5 text-gray-600" />
        </button>
        <button type="button" data-command="underline" class="toolbar-item underline">
            <x-heroicon-s-underline class="w-5 h-5 text-gray-600" />
        </button>
        <button type="button" data-command="bullet" class="toolbar-item">
            <x-heroicon-s-list-bullet class="w-5 h-5 text-gray-600" />
        </button>
        <button type="button" data-command="ordered" class="toolbar-item">
            <x-heroicon-s-numbered-list class="w-5 h-5 text-gray-600" />
        </button>
        <button type="button" data-command="blockquote" class="toolbar-item" title="Blockquote">
            <x-heroicon-s-chat-bubble-oval-left-ellipsis class="w-5 h-5 text-gray-600" />
        </button>
        <select data-command="callout" class="toolbar-item text-sm" title="Inserir callout">
            <option value="">💬 Callout…</option>
            <option value="NOTE">📝 Nota</option>
            <option value="TIP">💡 Dica</option>
            <option value="WARNING">⚠️ Atenção</option>
            <option value="DANGER">🚨 Cuidado</option>
        </select>
        <button type="button" data-command="code" class="toolbar-item">
            <x-heroicon-s-code-bracket class="w-5 h-5 text-gray-600" />
        </button>
        <button type="button" data-command="link" class="toolbar-item">
            <x-heroicon-s-link class="w-5 h-5 text-gray-600" />
        </button>
        <button type="button" data-command="align-left" class="toolbar-item">
            <x-heroicon-s-bars-3-bottom-left class="w-5 h-5 text-gray-600" />
        </button>
        <button type="button" data-command="align-center" class="toolbar-item">
            <x-heroicon-s-bars-3 class="w-5 h-5 text-gray-600" />
        </button>
        <button type="button" data-command="align-right" class="toolbar-item">
            <x-heroicon-s-bars-3-bottom-right class="w-5 h-5 text-gray-600" />
        </button>
        <button type="button" data-command="undo" class="toolbar-item">
            <x-heroicon-s-arrow-uturn-left class="w-5 h-5 text-gray-600" />
        </button>
        <button type="button" data-command="redo" class="toolbar-item">
            <x-heroicon-s-arrow-uturn-right class="w-5 h-5 text-gray-600" />
        </button>

        {{-- Ações de modo — empurradas para a direita --}}
        <div class="ml-auto flex items-center gap-1">
            <button type="button" data-command="toggle-code" class="toolbar-item" title="Alternar WYSIWYG / HTML">
                <x-heroicon-o-code-bracket-square class="w-5 h-5 text-gray-600" />
            </button>
            <button type="button" data-command="toggle-maximize" class="toolbar-item" title="Maximizar editor">
                <x-heroicon-o-arrows-pointing-out class="w-5 h-5 text-gray-600" data-icon="expand" />
                <x-heroicon-o-arrows-pointing-in  class="w-5 h-5 text-gray-600 hidden" data-icon="collapse" />
            </button>
        </div>
    </div>
    <div class="editor p-4 min-h-40 overflow-y-auto"></div>
    <textarea data-code-area
              class="hidden w-full font-mono text-sm p-4 min-h-40 resize-none bg-gray-50 focus:outline-none"
              spellcheck="false"></textarea>
</div>
<textarea class="hidden" data-editor-initial="{{ $id }}">{!! $initial !!}</textarea>
<input type="hidden" name="{{ $name }}" value="{{ $value }}" data-editor-target="{{ $id }}">
