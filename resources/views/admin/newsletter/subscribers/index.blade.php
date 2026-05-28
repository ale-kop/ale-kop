<x-layout title="Newsletter · Inscritos">
    <x-container>

        <div class="mb-6 flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Inscritos</h1>
                <p class="text-sm text-gray-500 mt-0.5">Pessoas cadastradas na newsletter</p>
            </div>
        </div>

        {{-- Contadores --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
            @foreach([
                ['label' => 'Total', 'value' => $counts['total'], 'color' => 'text-gray-700', 'filter' => null],
                ['label' => 'Ativos', 'value' => $counts['active'], 'color' => 'text-green-600', 'filter' => 'active'],
                ['label' => 'Descadastrados', 'value' => $counts['unsubscribed'], 'color' => 'text-red-500', 'filter' => 'unsubscribed'],
                ['label' => 'Bounced', 'value' => $counts['bounced'], 'color' => 'text-yellow-600', 'filter' => 'bounced'],
            ] as $stat)
                <a href="{{ route('admin.newsletter.subscribers.index', $stat['filter'] ? ['status' => $stat['filter']] : []) }}"
                   class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center
                          hover:border-brand transition-colors {{ request('status') === $stat['filter'] ? 'ring-2 ring-brand/30 border-brand' : '' }}">
                    <div class="text-2xl font-bold {{ $stat['color'] }}">{{ number_format($stat['value']) }}</div>
                    <div class="text-xs text-gray-500 mt-0.5">{{ $stat['label'] }}</div>
                </a>
            @endforeach
        </div>

        {{-- Filtros --}}
        <form method="GET" action="{{ route('admin.newsletter.subscribers.index') }}"
              class="flex flex-wrap gap-3 mb-4">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Buscar por nome ou e-mail…"
                   class="flex-1 min-w-48 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800
                          text-sm px-3 py-2 text-gray-900 dark:text-gray-100 placeholder:text-gray-400
                          focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand">

            <select name="list"
                    class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800
                           text-sm px-3 py-2 text-gray-700 dark:text-gray-300
                           focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand">
                <option value="">Todas as listas</option>
                @foreach($lists as $list)
                    <option value="{{ $list->id }}" @selected(request('list') == $list->id)>{{ $list->name }}</option>
                @endforeach
            </select>

            <select name="status"
                    class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800
                           text-sm px-3 py-2 text-gray-700 dark:text-gray-300
                           focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand">
                <option value="">Todos os status</option>
                <option value="active" @selected(request('status') === 'active')>Ativo</option>
                <option value="unsubscribed" @selected(request('status') === 'unsubscribed')>Descadastrado</option>
                <option value="bounced" @selected(request('status') === 'bounced')>Bounced</option>
            </select>

            <button type="submit"
                    class="px-4 py-2 rounded-lg bg-brand text-white text-sm font-semibold hover:bg-brand/90 transition-colors">
                Filtrar
            </button>

            @if(request()->hasAny(['search', 'list', 'status']))
                <a href="{{ route('admin.newsletter.subscribers.index') }}"
                   class="px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 text-sm text-gray-500 hover:text-gray-700 transition-colors">
                    Limpar
                </a>
            @endif
        </form>

        {{-- Tabela --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            @if($subscribers->isEmpty())
                <div class="px-6 py-12 text-center text-gray-400 text-sm">Nenhum inscrito encontrado.</div>
            @else
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nome / E-mail</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Listas</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Inscrito em</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($subscribers as $subscriber)
                            @php
                                $statusColor = match($subscriber->status) {
                                    'active' => 'bg-green-100 text-green-700',
                                    'unsubscribed' => 'bg-red-100 text-red-600',
                                    'bounced' => 'bg-yellow-100 text-yellow-700',
                                    default => 'bg-gray-100 text-gray-600',
                                };
                                $statusLabel = match($subscriber->status) {
                                    'active' => 'Ativo',
                                    'unsubscribed' => 'Descadastrado',
                                    'bounced' => 'Bounced',
                                    default => $subscriber->status,
                                };
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $subscriber->name ?: '—' }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ $subscriber->email }}</div>
                                </td>
                                <td class="px-4 py-3 hidden sm:table-cell">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($subscriber->lists as $list)
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                                {{ $list->name }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-gray-300">—</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold {{ $statusColor }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right text-xs text-gray-400 hidden md:table-cell">
                                    {{ $subscriber->subscribed_at?->format('d/m/Y') ?? '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($subscribers->hasPages())
                    <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
                        {{ $subscribers->links() }}
                    </div>
                @endif
            @endif
        </div>

        <div class="mt-4 text-xs text-gray-400">
            {{ $subscribers->total() }} resultado(s) encontrado(s)
        </div>

        <div class="mt-3">
            <a href="{{ route('admin.index') }}" class="text-sm text-gray-400 hover:text-gray-600 transition-colors">← Painel</a>
        </div>

    </x-container>
</x-layout>
