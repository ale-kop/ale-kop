{{--
    Convite de inscrição que abre ao rolar ~40% do texto, no estilo Substack.
    Controlado pelo módulo JS "article" ([data-article-popup]); ao fechar, não reaparece por 30 dias.
    Depois da inscrição o pop-up fica aberto mostrando a confirmação e nunca mais reaparece.
--}}
<dialog id="article-subscribe-popup" class="article-popup" data-article-popup aria-labelledby="article-popup-title">
    <button type="button" class="article-popup-close" data-article-popup-close aria-label="Fechar">
        <x-heroicon-o-x-mark class="size-5" />
    </button>

    <h2 id="article-popup-title">Receba os próximos posts</h2>
    <p>Um e-mail quando sair conteúdo novo. Sem spam.</p>

    <x-newsletter.form id="article-popup" form="post-popup" />

    <button type="button" class="article-popup-skip" data-article-popup-close>Não, obrigado. Continuar lendo</button>
</dialog>
