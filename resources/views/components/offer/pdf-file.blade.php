{{-- Anexo PDF dentro de um balão de conversa --}}
@props(['name', 'pages' => 1])
<div class="bubble-file">
    <span class="bubble-file-icon" aria-hidden="true">PDF</span>
    <span class="min-w-0">
        <span class="block truncate font-medium">{{ $name }}</span>
        <span class="block text-xs text-[#667781]">PDF, {{ $pages }} {{ $pages > 1 ? 'páginas' : 'página' }}</span>
    </span>
</div>
