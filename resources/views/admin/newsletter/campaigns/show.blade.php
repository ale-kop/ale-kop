<x-layout title="{{ $campaign->title }} · Newsletter">
    <x-container>
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

        <div class="mb-6 flex items-start justify-between gap-4 flex-wrap">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $campaign->title }}</h1>
                <div class="flex items-center gap-3 mt-1">
                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold {{ $statusColors[$campaign->status] ?? '' }}">
                        {{ $statusLabels[$campaign->status] ?? $campaign->status }}
                    </span>
                    <span class="text-xs text-gray-400">Criado por {{ $campaign->createdBy?->name ?? '—' }}</span>
                    @if($campaign->sent_at)
                        <span class="text-xs text-gray-400">· Iniciado em {{ $campaign->sent_at->format('d/m/Y H:i') }}</span>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                @if($campaign->isDraft() || $campaign->isScheduled())
                    <a href="{{ route('admin.newsletter.campaigns.edit', $campaign) }}"
                       class="px-4 py-2 rounded-lg border border-gray-200 text-sm font-semibold hover:border-brand hover:text-brand transition-colors">
                        Editar
                    </a>
                    <form action="{{ route('admin.newsletter.campaigns.send', $campaign) }}" method="POST"
                          onsubmit="return confirm('Confirmar envio desta campanha para todos os inscritos nas listas selecionadas?')">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 rounded-lg bg-brand text-white text-sm font-semibold hover:bg-brand/90 transition-colors">
                            Enviar agora
                        </button>
                    </form>
                @endif
                @if(in_array($campaign->status, ['scheduled', 'draft']))
                    <form action="{{ route('admin.newsletter.campaigns.cancel', $campaign) }}" method="POST"
                          onsubmit="return confirm('Cancelar esta campanha?')">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded-lg border border-red-200 text-red-600 text-sm font-semibold hover:bg-red-50 transition-colors">
                            Cancelar
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Status stats --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
            @php
                $stats = [
                    ['label' => 'Total', 'value' => $campaign->total_recipients, 'color' => 'text-gray-700'],
                    ['label' => 'Enviados', 'value' => $campaign->sent_count, 'color' => 'text-green-600'],
                    ['label' => 'Falhas', 'value' => $campaign->failed_count, 'color' => 'text-red-500'],
                    ['label' => 'Pendentes', 'value' => $campaign->pendingCount(), 'color' => 'text-yellow-600'],
                ];
            @endphp
            @foreach($stats as $stat)
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
                    <div class="text-2xl font-bold {{ $stat['color'] }}">{{ number_format($stat['value']) }}</div>
                    <div class="text-xs text-gray-500 mt-0.5">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>

        @if($campaign->total_recipients > 0)
            <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center justify-between mb-2 text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Progresso de envio</span>
                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $campaign->completionPercentage() }}%</span>
                </div>
                <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2">
                    <div class="bg-brand rounded-full h-2 transition-all"
                         style="width: {{ $campaign->completionPercentage() }}%"></div>
                </div>
            </div>
        @endif

        {{-- Campaign details --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Conteúdo</h2>
                <p class="text-xs text-gray-400 mb-1">Assunto: <span class="text-gray-700 dark:text-gray-300">{{ $campaign->subject }}</span></p>
                <div class="mt-4 prose prose-sm max-w-none text-gray-700 dark:text-gray-300 border border-dashed border-gray-200 rounded-lg p-4">
                    {!! $campaign->content !!}
                </div>
            </div>

            <div class="space-y-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                    <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Listas</h2>
                    @forelse($campaign->lists as $list)
                        <div class="flex items-center justify-between py-1.5">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $list->name }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400">Nenhuma lista vinculada</p>
                    @endforelse
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                    <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Datas</h2>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Criado</dt>
                            <dd class="text-gray-700 dark:text-gray-300">{{ $campaign->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        @if($campaign->scheduled_at)
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Agendado</dt>
                                <dd class="text-gray-700 dark:text-gray-300">{{ $campaign->scheduled_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        @endif
                        @if($campaign->sent_at)
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Iniciado</dt>
                                <dd class="text-gray-700 dark:text-gray-300">{{ $campaign->sent_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        @if($campaign->isProcessing() || $campaign->status === 'completed')
            <div class="mt-4 text-xs text-gray-400">
                A página não atualiza automaticamente.
                <a href="{{ route('admin.newsletter.campaigns.show', $campaign) }}" class="underline hover:text-gray-600">Recarregar</a>
            </div>
        @endif

        <div class="mt-4">
            <a href="{{ route('admin.newsletter.campaigns.index') }}" class="text-sm text-gray-400 hover:text-gray-600 transition-colors">← Campanhas</a>
        </div>
    </x-container>
</x-layout>
