<x-layout title="Nova Campanha · Newsletter">
    <x-container class="max-w-2xl">
        <h1 class="text-2xl font-bold mb-6">Nova Campanha de Newsletter</h1>

        <form action="{{ route('admin.newsletter.campaigns.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <x-forms.label for="title">Título interno</x-forms.label>
                <x-forms.input id="title" name="title" type="text" :value="old('title')"
                               placeholder="Ex: Newsletter maio/2026" required />
            </div>

            <div>
                <x-forms.label for="subject">Assunto do e-mail</x-forms.label>
                <x-forms.input id="subject" name="subject" type="text" :value="old('subject')"
                               placeholder="Aparece no cliente de e-mail do destinatário" required />
            </div>

            <div>
                <x-forms.label>Listas</x-forms.label>
                <div class="mt-2 space-y-2">
                    @foreach($lists as $list)
                        <label class="flex items-center gap-3 cursor-pointer">
                            <x-forms.checkbox name="list_ids[]" :value="$list->id"
                                              :checked="in_array($list->id, old('list_ids', []))" />
                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                {{ $list->name }}
                                <span class="text-gray-400 text-xs">({{ number_format($list->subscribers_count) }} ativos)</span>
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <x-forms.label for="scheduled_at">Agendar envio (opcional)</x-forms.label>
                <x-forms.input id="scheduled_at" name="scheduled_at" type="datetime-local"
                               :value="old('scheduled_at')" />
                <p class="text-xs text-gray-400 mt-1">Deixe em branco para salvar como rascunho.</p>
            </div>

            <div>
                <x-forms.label for="content">Conteúdo do e-mail</x-forms.label>
                <x-forms.rich-text-editor id="content" name="content" :value="old('content')" />
            </div>

            @if($errors->any())
                <ul class="text-sm text-red-600 space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            @endif

            <div class="flex gap-3">
                <x-forms.button type="submit">Salvar campanha</x-forms.button>
                <a href="{{ route('admin.newsletter.campaigns.index') }}"
                   class="px-3 py-2 rounded border hover:bg-gray-50 dark:hover:bg-gray-800 text-sm">Cancelar</a>
            </div>
        </form>
    </x-container>
</x-layout>
