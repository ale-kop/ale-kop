<x-layout title="Newsletter · Campanhas">
    <x-container>
        <div class="mb-6 flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Campanhas de Newsletter</h1>
                <p class="text-sm text-gray-500 mt-0.5">Crie e envie campanhas de e-mail</p>
            </div>
            <a href="{{ route('admin.newsletter.campaigns.create') }}"
               class="px-4 py-2 rounded-lg bg-brand text-white text-sm font-semibold hover:bg-brand/90 transition-colors">
                + Nova Campanha
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            @if($campaigns->isEmpty())
                <div class="px-6 py-12 text-center text-gray-400 text-sm">Nenhuma campanha criada ainda.</div>
            @else
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Título</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Progresso</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Agendado</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($campaigns as $campaign)
                            @php
                                $statusColors = [
                                    'draft' => 'bg-gray-100 text-gray-600',
                                    'scheduled' => 'bg-blue-100 text-blue-700',
                                    'processing' => 'bg-yellow-100 text-yellow-700',
                                    'completed' => 'bg-green-100 text-green-700',
                                    'cancelled' => 'bg-red-100 text-red-600',
                                ];
                                $statusLabels = [
                                    'draft' => 'Rascunho',
                                    'scheduled' => 'Agendado',
                                    'processing' => 'Enviando',
                                    'completed' => 'Concluído',
                                    'cancelled' => 'Cancelado',
                                ];
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.newsletter.campaigns.show', $campaign) }}"
                                       class="font-medium text-gray-900 dark:text-gray-100 hover:text-brand transition-colors">
                                        {{ $campaign->title }}
                                    </a>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $campaign->subject }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold {{ $statusColors[$campaign->status] ?? '' }}">
                                        {{ $statusLabels[$campaign->status] ?? $campaign->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right text-xs text-gray-500">
                                    @if($campaign->total_recipients > 0)
                                        {{ $campaign->sent_count }}/{{ $campaign->total_recipients }}
                                        <span class="text-gray-300">({{ $campaign->completionPercentage() }}%)</span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-500">
                                    {{ $campaign->scheduled_at?->format('d/m/Y H:i') ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($campaign->isDraft() || $campaign->isScheduled())
                                            <a href="{{ route('admin.newsletter.campaigns.edit', $campaign) }}"
                                               class="text-xs text-brand hover:underline">Editar</a>
                                        @endif
                                        <a href="{{ route('admin.newsletter.campaigns.show', $campaign) }}"
                                           class="text-xs text-gray-500 hover:underline">Ver</a>
                                        @if($campaign->isDraft() || $campaign->isScheduled())
                                            <form action="{{ route('admin.newsletter.campaigns.destroy', $campaign) }}" method="POST"
                                                  onsubmit="return confirm('Remover esta campanha?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-xs text-red-500 hover:underline">Remover</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($campaigns->hasPages())
                    <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
                        {{ $campaigns->links() }}
                    </div>
                @endif
            @endif
        </div>

        <div class="mt-4">
            <a href="{{ route('admin.index') }}" class="text-sm text-gray-400 hover:text-gray-600 transition-colors">← Painel</a>
        </div>
    </x-container>
</x-layout>
