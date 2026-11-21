{{--
    Escolha das listas de uma campanha, com o alcance real ao lado (módulo JS "newsletterAudience").
    O alcance conta cada pessoa uma vez, mesmo que ela esteja em várias listas marcadas.

    $lists:    NewsletterList com active_subscribers_count (scope withActiveCount).
    $selected: ids marcados.
--}}
@props(['lists', 'selected' => []])

<div data-nl-audience data-audience-url="{{ route('admin.newsletter.campaigns.audience') }}">
    <x-forms.label>Para quem vai</x-forms.label>
    <p class="text-xs text-gray-400 mt-1">
        Toda inscrição confirmada entra na lista geral. Marque só ela para falar com todo mundo,
        ou só uma lista de interesse para falar com quem pediu aquele assunto.
    </p>

    <div class="mt-3 space-y-2">
        @foreach($lists as $list)
            <label class="flex items-start gap-3 cursor-pointer rounded-lg border border-gray-200 dark:border-gray-700 px-3 py-2 hover:border-brand transition-colors">
                <x-forms.checkbox :id="'list-'.$list->id" name="list_ids[]" :value="$list->id" :checked="in_array($list->id, $selected)" data-nl-audience-list />
                <span class="text-sm text-gray-700 dark:text-gray-300">
                    {{ $list->name }}
                    @if($list->slug === \App\Models\NewsletterList::DEFAULT_SLUG)
                        <span class="text-xs text-brand font-semibold">· geral</span>
                    @endif
                    <span class="text-gray-400 text-xs">({{ number_format($list->active_subscribers_count) }} ativos)</span>
                    @if($list->description)
                        <span class="block text-xs text-gray-400">{{ $list->description }}</span>
                    @endif
                </span>
            </label>
        @endforeach
    </div>

    <p class="mt-3 text-sm font-semibold text-gray-900 dark:text-gray-100" data-nl-audience-total aria-live="polite"></p>
</div>
