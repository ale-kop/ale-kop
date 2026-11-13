{{--
    Página de oferta — "Orçamentos e propostas comerciais pelo WhatsApp".
    Nome e preço vêm do curso (config/offers.php → course_slug). A copy é desta view.
    Estilos: resources/css/components/offer.css
    Trechos marcados com PLACEHOLDER precisam ser confirmados antes de publicar.
--}}
@php
    $checkoutUrl = route('checkout.show', $course->slug);

    // PLACEHOLDER: módulos, aulas e durações do curso.
    $modules = [
        [
            'title' => 'Seu processo de orçamento no papel',
            'summary' => 'Antes de automatizar, você organiza o que já faz: quais serviços vende, como calcula o preço e o que todo orçamento seu precisa dizer.',
            'lessons' => ['Os pedidos de orçamento que chegam pelo WhatsApp', 'Sua tabela de preços: m², hora, diária ou pacote', 'Condições de pagamento, prazo e validade'],
        ],
        [
            'title' => 'O modelo do documento',
            'summary' => 'Você monta o modelo de orçamento e o de proposta com a sua marca. É neles que o assistente vai escrever.',
            'lessons' => ['Orçamento de uma página para serviço com preço definido', 'Proposta comercial para venda consultiva', 'Logo, cores e dados da empresa'],
        ],
        [
            'title' => 'O assistente no seu WhatsApp',
            'summary' => 'Você conecta o assistente a um número de WhatsApp e troca as primeiras mensagens com ele.',
            'lessons' => ['Criar e conectar o assistente', 'Quem pode falar com ele, e quem não pode', 'Primeiro teste: “orçamento de teste para mim”'],
        ],
        [
            'title' => 'Do pedido ao PDF',
            'summary' => 'Você ensina o assistente a entender sua mensagem, consultar a tabela, fazer as contas e preencher o modelo.',
            'lessons' => ['Como escrever o pedido para o assistente', 'Cálculo de itens, descontos e entrada', 'Revisar antes de enviar: “troca a validade para 15 dias”'],
        ],
        [
            'title' => 'Propostas para vendas consultivas',
            'summary' => 'Depois da conversa com o cliente, um áudio seu com o resumo vira uma proposta com contexto, solução, etapas e investimento.',
            'lessons' => ['O resumo da conversa em áudio', 'Escopo, etapas e prazo escritos do seu jeito', 'Duas opções de investimento na mesma proposta'],
        ],
        [
            'title' => 'Enviar e acompanhar',
            'summary' => 'O assistente envia o documento ao cliente, numera cada orçamento e guarda o histórico para você consultar depois.',
            'lessons' => ['Envio direto para o cliente', 'Numeração e histórico de orçamentos', 'Lembrete para orçamentos sem resposta'],
        ],
    ];

    // PLACEHOLDER: respostas sobre ferramentas e custos dependem da stack usada no curso.
    $faq = [
        ['Preciso saber programar?', 'Não. Você vai configurar tudo por telas e mensagens, seguindo as aulas passo a passo. Se você usa WhatsApp e já editou uma planilha, consegue fazer.'],
        ['Funciona com o meu número de WhatsApp?', 'O assistente funciona em um número de WhatsApp separado do seu, que você conversa como se fosse um contato. Na aula sobre conexão eu mostro as opções e o que cada uma exige.'],
        ['Tem algum custo além do curso?', 'As ferramentas usadas no curso têm plano gratuito para começar. Se o volume de orçamentos crescer, mostro quanto custa cada uma e quando vale a pena trocar de plano.'],
        ['Serve para o meu tipo de negócio?', 'Serve para quem recebe pedidos de orçamento pelo WhatsApp: prestadores de serviço com preço de tabela e quem vende serviços sob medida depois de entender o cliente. O curso mostra os dois casos.'],
        ['Por quanto tempo tenho acesso?', 'Por 1 ano a partir da compra, incluindo as aulas novas e as atualizações publicadas nesse período.'],
        ['Como recebo o acesso?', 'Assim que o pagamento é aprovado, você recebe um e-mail com o link para criar sua senha e entrar. No Pix e no cartão aprovado, leva poucos minutos.'],
        ['E se eu não gostar?', 'Você tem 7 dias a partir da compra para pedir o reembolso, sem precisar explicar o motivo. É só responder ao e-mail de confirmação.'],
    ];
@endphp

<x-layout :title="$course->name"
          :description="data_get($course->meta, 'description') ?? 'Aprenda a mandar uma mensagem para o seu WhatsApp e receber o orçamento ou a proposta pronta para enviar ao cliente.'"
          :decoration="false">

<div class="offer">

    {{-- ═══ HERO ═══════════════════════════════════════════════════════ --}}
    <section class="relative">
        <x-container class="grid items-center gap-x-16 gap-y-14 pb-20 pt-6 lg:grid-cols-[1fr_380px] lg:pb-28">
            <div class="max-w-2xl">
                <p class="font-sans text-base font-semibold text-brand-deep">Curso online</p>
                <h1 class="mt-3 font-sans text-[2.6rem]/[1.05] font-bold tracking-[-0.04em] text-pretty text-[var(--ink)] sm:text-6xl/[1.02]">
                    Seu próximo orçamento sai de uma mensagem no WhatsApp
                </h1>
                <p class="mt-7 font-serif text-xl/8 text-gray-600 sm:text-[1.375rem]/9">
                    Você já atende, cota e negocia pelo WhatsApp. Neste curso, você monta um assistente que recebe
                    o pedido em uma mensagem sua, gera o orçamento ou a proposta em PDF e envia ao cliente em instantes.
                </p>

                <div class="mt-10 flex flex-wrap items-center gap-x-6 gap-y-4">
                    <a href="{{ $checkoutUrl }}"
                       class="inline-flex items-center rounded-full bg-brand-deep px-7 py-3.5 font-sans text-base font-semibold text-white shadow-md shadow-brand-deep/20 transition-colors hover:bg-[#0F3A57] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand">
                        Comprar o curso
                    </a>
                    <a href="#conteudo" class="font-sans text-base font-semibold text-gray-700 underline decoration-gray-300 underline-offset-4 hover:decoration-gray-700">
                        Ver o que você vai aprender
                    </a>
                </div>
                <p class="mt-5 font-sans text-sm text-gray-500">
                    {{ $course->formattedPrice() }} no Pix ou em até {{ $maxInstallments }}x no cartão. 7 dias de garantia.
                </p>
            </div>

            {{-- Conversa com o assistente (anima uma vez no load) --}}
            <div class="flex justify-center lg:justify-end">
                <div class="phone" role="img"
                     aria-label="Conversa no WhatsApp: você pede um orçamento ao assistente, ele responde com o PDF e envia para a cliente.">
                    <div class="phone-screen" aria-hidden="true">
                        <div class="chat-head">
                            <span class="chat-avatar">
                                <x-heroicon-s-document-text class="size-5"/>
                            </span>
                            <span class="min-w-0 font-sans leading-tight">
                                <span class="block truncate text-[15px] font-semibold text-[#111B21]">Assistente de orçamentos</span>
                                <span class="block text-xs text-[#667781]">online</span>
                            </span>
                        </div>

                        <div class="chat-body chat-play">
                            <x-offer.bubble from="me" time="09:12" read style="--at: .4s">
                                Orçamento para Marina Lopes, (11) 98765-4321. Limpeza pós-obra, apartamento de 80 m²,
                                2 diárias com 2 pessoas. Material incluso, 50% de entrada.
                            </x-offer.bubble>

                            <div class="typing" style="--at: 1.3s; --for: 1.4s"><i></i><i></i><i></i></div>

                            <x-offer.bubble from="them" time="09:12" style="--at: 2.7s">
                                <x-offer.pdf-file name="Orcamento-142-Marina-Lopes.pdf"/>
                                Orçamento nº 142 pronto. Total de R$ 1.860,00, entrada de R$ 930,00, validade de 7 dias.
                                Envio para a Marina?
                            </x-offer.bubble>

                            <x-offer.bubble from="me" time="09:13" read style="--at: 4s">
                                Pode enviar
                            </x-offer.bubble>

                            <div class="typing" style="--at: 4.6s; --for: 1s"><i></i><i></i><i></i></div>

                            <x-offer.bubble from="them" time="09:13" style="--at: 5.6s">
                                Enviado para Marina Lopes às 09:13. Aviso você quando ela responder.
                            </x-offer.bubble>
                        </div>

                        <div class="chat-compose">
                            <span class="chat-compose-field">Mensagem</span>
                            <span class="chat-compose-mic"><x-heroicon-s-microphone class="size-5"/></span>
                        </div>
                    </div>
                </div>
            </div>
        </x-container>
    </section>

    {{-- ═══ HOJE × COM O ASSISTENTE ════════════════════════════════════ --}}
    <section class="bg-[var(--tint)] py-20 sm:py-28">
        <x-container>
            <div class="max-w-3xl">
                <h2 class="font-sans text-3xl font-bold tracking-[-0.03em] text-[var(--ink)] sm:text-[2.75rem]/[1.1]">
                    Quem responde primeiro sai na frente
                </h2>
                <p class="mt-5 font-serif text-xl/8 text-gray-600">
                    Um orçamento bom que chega à noite perde para um orçamento razoável que chega agora.
                    Veja o mesmo pedido, feito de dois jeitos.
                </p>
            </div>

            <div class="mt-14 grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl bg-white p-7 ring-1 ring-black/5 sm:p-10">
                    <h3 class="font-sans text-xl font-bold tracking-[-0.02em] text-gray-500">Como costuma ser</h3>
                    <ol class="timeline mt-8 font-serif text-lg/7 text-gray-600">
                        <li><time>09:12</time><span>A cliente pede um orçamento de limpeza pós-obra.</span></li>
                        <li><time>09:15</time><span>Você responde “já te mando” e segue com o dia.</span></li>
                        <li><time>19:40</time><span>À noite, abre o orçamento antigo no Word e troca nome, itens e valores.</span></li>
                        <li><time>20:30</time><span>Confere as contas, exporta o PDF e envia.</span></li>
                        <li><time class="text-accent">10:05</time><span>No dia seguinte: “Obrigada, mas já fechei com outra empresa.”</span></li>
                    </ol>
                </div>

                <div class="rounded-2xl bg-brand-deep p-7 text-white sm:p-10">
                    <h3 class="font-sans text-xl font-bold tracking-[-0.02em]">Com o assistente</h3>
                    <ol class="timeline mt-8 font-serif text-lg/7 text-white/85">
                        <li><time class="text-white">09:12</time><span>A cliente pede um orçamento de limpeza pós-obra.</span></li>
                        <li><time class="text-white">09:13</time><span>Você manda ao assistente o resumo do pedido, por texto ou áudio.</span></li>
                        <li><time class="text-white">09:13</time><span>Ele monta o PDF com sua marca, itens, condições e validade, e mostra para você.</span></li>
                        <li><time class="text-white">09:14</time><span>Você responde “pode enviar”. A cliente recebe o orçamento enquanto ainda está com o celular na mão.</span></li>
                    </ol>
                </div>
            </div>
        </x-container>
    </section>

    {{-- ═══ DOIS JEITOS DE VENDER ══════════════════════════════════════ --}}
    <section class="py-20 sm:py-28">
        <x-container>
            <div class="max-w-3xl">
                <h2 class="font-sans text-3xl font-bold tracking-[-0.03em] text-[var(--ink)] sm:text-[2.75rem]/[1.1]">
                    Para preço de tabela e para venda consultiva
                </h2>
                <p class="mt-5 font-serif text-xl/8 text-gray-600">
                    O curso mostra os dois casos, do pedido simples à proposta que depende de entender o cliente.
                </p>
            </div>

            <div class="mt-14 grid gap-x-16 gap-y-16 lg:grid-cols-2">
                {{-- Serviço com preço definido --}}
                <article>
                    <div class="flex min-h-56 flex-col justify-center rounded-2xl bg-[var(--chat-wall)] p-5 sm:p-7">
                        <div class="flex flex-col gap-1.5">
                            <x-offer.bubble from="me" time="14:02" read>
                                Orçamento para o Ricardo: pintura de 3 cômodos, 42 m² de parede, tinta acetinada.
                            </x-offer.bubble>
                            <x-offer.bubble from="them" time="14:02">
                                <x-offer.pdf-file name="Orcamento-143-Ricardo.pdf"/>
                                42 m² × R$ 28,00 + tinta (3 galões). Total de R$ 1.716,00. Envio?
                            </x-offer.bubble>
                        </div>
                    </div>
                    <h3 class="mt-8 font-sans text-2xl font-bold tracking-[-0.025em] text-[var(--ink)]">Serviço com preço definido</h3>
                    <p class="mt-3 font-serif text-lg/8 text-gray-600">
                        Você cobra por m², hora, diária ou pacote. O assistente consulta a sua tabela, faz as contas
                        e aplica suas condições de pagamento. Você só confere e manda enviar.
                    </p>
                </article>

                {{-- Venda consultiva --}}
                <article>
                    <div class="flex min-h-56 flex-col justify-center rounded-2xl bg-[var(--chat-wall)] p-5 sm:p-7">
                        <div class="flex flex-col gap-1.5">
                            <x-offer.bubble from="me" time="17:40" read>
                                <span class="bubble-voice">
                                    <x-heroicon-s-play class="size-5 text-[#54656F]"/>
                                    <svg class="bubble-voice-wave" viewBox="0 0 150 22" preserveAspectRatio="none" aria-hidden="true">
                                        @foreach([6,10,14,8,18,12,20,9,15,6,11,17,13,7,19,10,14,8,12,16,6,10,13,9,17,11,7,14,10,6] as $i => $h)
                                            <rect x="{{ $i * 5 }}" y="{{ (22 - $h) / 2 }}" width="2.4" height="{{ $h }}" rx="1.2" fill="currentColor"/>
                                        @endforeach
                                    </svg>
                                    <span class="text-xs text-[#667781]">0:48</span>
                                </span>
                            </x-offer.bubble>
                            <x-offer.bubble from="them" time="17:41">
                                <x-offer.pdf-file name="Proposta-Clinica-Vida.pdf" :pages="4"/>
                                Proposta com contexto, solução em 3 etapas, prazo de 60 dias e duas opções de investimento.
                            </x-offer.bubble>
                        </div>
                    </div>
                    <h3 class="mt-8 font-sans text-2xl font-bold tracking-[-0.025em] text-[var(--ink)]">Venda consultiva</h3>
                    <p class="mt-3 font-serif text-lg/8 text-gray-600">
                        Você conversa, entende o problema e decide o escopo. Depois manda um áudio com o resumo,
                        e o assistente escreve a proposta: contexto, solução, etapas, prazo e investimento.
                    </p>
                </article>
            </div>
        </x-container>
    </section>

    {{-- ═══ O QUE O CLIENTE RECEBE ═════════════════════════════════════ --}}
    <section class="overflow-hidden bg-[var(--tint)] py-20 sm:py-28">
        <x-container class="grid items-center gap-x-20 gap-y-14 lg:grid-cols-2">
            <div class="flex justify-center lg:order-2">
                {{-- Orçamento nº 142, o mesmo da conversa do topo --}}
                <div class="doc-sheet" role="img" aria-label="Exemplo de orçamento em PDF gerado pelo assistente">
                    <div aria-hidden="true">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-center gap-2">
                                <span class="grid size-8 place-items-center rounded-md bg-[#2F7D6B] text-white"><x-heroicon-s-sparkles class="size-4"/></span>
                                <span class="text-[13px] font-bold leading-tight">Limpa Bem<br><span class="font-normal text-gray-500">Serviços de limpeza</span></span>
                            </div>
                            <div class="text-right text-[11px] leading-snug text-gray-500">
                                <span class="block text-[13px] font-bold text-[#2A2F3B]">Orçamento nº 142</span>
                                13 de novembro de 2026
                            </div>
                        </div>

                        <div class="mt-[9%] text-[11px] leading-snug text-gray-500">
                            Para <span class="block text-[13px] font-semibold text-[#2A2F3B]">Marina Lopes</span>
                        </div>

                        <table class="mt-[7%] w-full text-[11px] leading-snug">
                            <thead>
                                <tr class="border-b border-gray-200 text-left text-gray-500">
                                    <th class="pb-1.5 font-medium">Item</th>
                                    <th class="pb-1.5 text-right font-medium">Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-gray-100">
                                    <td class="py-2 pr-2">Limpeza pós-obra, apto 80 m²<br><span class="text-gray-500">2 diárias × 2 pessoas × R$ 390,00</span></td>
                                    <td class="py-2 text-right">R$ 1.560,00</td>
                                </tr>
                                <tr class="border-b border-gray-100">
                                    <td class="py-2 pr-2">Material de limpeza</td>
                                    <td class="py-2 text-right">R$ 300,00</td>
                                </tr>
                                <tr>
                                    <td class="pt-2.5 font-bold">Total</td>
                                    <td class="pt-2.5 text-right font-bold">R$ 1.860,00</td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-[8%] rounded-md bg-gray-50 p-3 text-[11px] leading-relaxed text-gray-600">
                            <span class="block font-semibold text-[#2A2F3B]">Condições</span>
                            Entrada de 50% (R$ 930,00) na aprovação e o restante ao fim do serviço. Pix ou transferência.
                            Orçamento válido por 7 dias.
                        </div>

                        <div class="mt-[8%] text-[11px] leading-snug text-gray-500">
                            Para aprovar, responda “aprovado” no WhatsApp.
                        </div>

                        <div class="absolute inset-x-[8%] bottom-[6%] flex justify-between border-t border-gray-100 pt-3 text-[10px] text-gray-400">
                            <span>Limpa Bem Serviços de Limpeza</span>
                            <span>(11) 4000-1234</span>
                        </div>
                    </div>

                    <span class="doc-key" style="top: 9%; left: -11px">1</span>
                    <span class="doc-key" style="top: 36%; right: -11px">2</span>
                    <span class="doc-key" style="top: 55%; left: -11px">3</span>
                    <span class="doc-key" style="top: 68%; right: -11px">4</span>
                </div>
            </div>

            <div class="lg:order-1">
                <h2 class="font-sans text-3xl font-bold tracking-[-0.03em] text-[var(--ink)] sm:text-[2.75rem]/[1.1]">
                    O cliente recebe um documento, não um rascunho
                </h2>
                <p class="mt-5 font-serif text-xl/8 text-gray-600">
                    O orçamento sai no seu modelo, do mesmo jeito todas as vezes. Ninguém precisa saber que ele foi feito em um minuto.
                </p>
                <ul class="mt-10 space-y-6 font-serif text-lg/7 text-gray-600">
                    <li class="flex gap-4"><span class="doc-key-inline mt-0.5">1</span><span><strong class="font-sans font-semibold text-[var(--ink)]">Sua marca.</strong> Logo, cores e dados da empresa no cabeçalho, com numeração automática.</span></li>
                    <li class="flex gap-4"><span class="doc-key-inline mt-0.5">2</span><span><strong class="font-sans font-semibold text-[var(--ink)]">Contas certas.</strong> Itens e valores calculados pela sua tabela, sem erro de digitação.</span></li>
                    <li class="flex gap-4"><span class="doc-key-inline mt-0.5">3</span><span><strong class="font-sans font-semibold text-[var(--ink)]">Condições claras.</strong> Entrada, forma de pagamento e validade, para o cliente não precisar perguntar.</span></li>
                    <li class="flex gap-4"><span class="doc-key-inline mt-0.5">4</span><span><strong class="font-sans font-semibold text-[var(--ink)]">Próximo passo.</strong> O cliente sabe como aprovar, e a resposta volta para o seu WhatsApp.</span></li>
                </ul>
            </div>
        </x-container>
    </section>

    {{-- ═══ CONTEÚDO DO CURSO ══════════════════════════════════════════ --}}
    <section id="conteudo" class="scroll-mt-28 py-20 sm:py-28">
        <x-container class="grid gap-x-16 gap-y-12 lg:grid-cols-[minmax(0,22rem)_1fr]">
            <div class="lg:sticky lg:top-32 lg:self-start">
                <h2 class="font-sans text-3xl font-bold tracking-[-0.03em] text-[var(--ink)] sm:text-[2.75rem]/[1.1]">
                    O que você vai aprender
                </h2>
                <p class="mt-5 font-serif text-xl/8 text-gray-600">
                    {{ count($modules) }} módulos em vídeo, na ordem em que você monta o seu assistente.
                    Ao fim do último, ele já está enviando orçamentos de verdade.
                </p>
                <a href="{{ $checkoutUrl }}"
                   class="mt-8 hidden items-center rounded-full bg-brand-deep px-6 py-3 font-sans text-sm font-semibold text-white transition-colors hover:bg-[#0F3A57] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand lg:inline-flex">
                    Comprar o curso
                </a>
            </div>

            <ol class="divide-y divide-gray-200 border-y border-gray-200">
                @foreach($modules as $module)
                    <li class="grid grid-cols-[2.5rem_1fr] gap-x-4 py-8 sm:grid-cols-[3.5rem_1fr]">
                        <span class="font-sans text-3xl font-bold tabular-nums tracking-[-0.03em] text-brand sm:text-4xl" aria-hidden="true">{{ $loop->iteration }}</span>
                        <div>
                            <h3 class="font-sans text-xl font-bold tracking-[-0.02em] text-[var(--ink)] sm:text-2xl">
                                <span class="sr-only">Módulo {{ $loop->iteration }}: </span>{{ $module['title'] }}
                            </h3>
                            <p class="mt-2 font-serif text-lg/7 text-gray-600">{{ $module['summary'] }}</p>
                            <ul class="mt-4 space-y-1.5 font-sans text-[15px] text-gray-600">
                                @foreach($module['lessons'] as $lesson)
                                    <li class="flex gap-2.5">
                                        <x-heroicon-o-play-circle class="mt-0.5 size-4 flex-none text-gray-400"/>
                                        {{ $lesson }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                @endforeach
            </ol>
        </x-container>
    </section>

    {{-- ═══ INSTRUTOR ══════════════════════════════════════════════════ --}}
    <section class="bg-[var(--tint)] py-20 sm:py-28">
        <x-container>
            <div class="grid items-center gap-x-16 gap-y-10 lg:grid-cols-[minmax(0,26rem)_1fr]">
                <img src="{{ asset('img/aleksandr-sitting-office-modern.png') }}"
                     alt="Aleksandr Kopelevitch sentado em seu escritório"
                     class="aspect-4/5 w-full rounded-3xl object-cover object-[60%_50%] shadow-xl shadow-black/10"
                     loading="lazy">
                <div class="max-w-2xl">
                    <h2 class="font-sans text-3xl font-bold tracking-[-0.03em] text-[var(--ink)] sm:text-[2.75rem]/[1.1]">
                        Quem ensina
                    </h2>
                    {{-- PLACEHOLDER: ajustar a bio ao curso --}}
                    <div class="mt-6 space-y-5 font-serif text-xl/8 text-gray-600">
                        <p>
                            Sou Aleksandr Kopelevitch, o Ale Kop. Há mais de 20 anos atendo empresas e pessoas em comunicação,
                            posicionamento e desenvolvimento de produtos, e escrevi muitos orçamentos e propostas nesse caminho.
                        </p>
                        <p>
                            Este curso junta as duas coisas: o que faz uma proposta ser entendida e aprovada, e o jeito mais rápido
                            de fazê-la chegar ao cliente pelo canal em que ele já está falando com você.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-20">
                <h3 class="font-sans text-lg font-semibold text-gray-500">Clientes atendidos</h3>
                <div class="mt-6 flex flex-wrap items-center gap-x-12 gap-y-8">
                    @foreach([
                        ['logo-arysta', 'Arysta'],
                        ['logo-iac', 'Instituto Agronômico de Campinas'],
                        ['logo-apta', 'APTA SP'],
                        ['logo-lider-leiloes', 'Líder Leilões'],
                        ['logo-fundepag', 'Fundepag'],
                        ['logo-capital-consultoria', 'Capital Consultoria'],
                    ] as [$file, $name])
                        <img class="h-9 w-auto object-contain opacity-70 mix-blend-multiply grayscale sm:h-10" src="{{ asset("img/{$file}.webp") }}" alt="{{ $name }}" loading="lazy">
                    @endforeach
                </div>
            </div>
        </x-container>
    </section>

    {{-- ═══ OFERTA ═════════════════════════════════════════════════════ --}}
    <section id="oferta" class="scroll-mt-28 py-20 sm:py-28">
        <x-container>
            <div class="mx-auto max-w-4xl rounded-[2rem] p-2 ring-1 ring-black/5 shadow-md shadow-black/5">
                <div class="grid overflow-hidden rounded-3xl bg-white ring-1 ring-black/5 md:grid-cols-[1fr_22rem]">
                    <div class="p-8 sm:p-12">
                        <h2 class="font-sans text-3xl font-bold tracking-[-0.03em] text-[var(--ink)]">{{ $course->name }}</h2>
                        <p class="mt-3 font-serif text-lg/7 text-gray-600">Tudo o que você precisa para o assistente começar a enviar orçamentos.</p>

                        {{-- PLACEHOLDER: itens inclusos --}}
                        <ul class="mt-8 grid gap-3 font-sans text-[15px] text-gray-700 sm:grid-cols-2">
                            @foreach([
                                count($modules).' módulos em vídeo, no seu ritmo',
                                'Modelo de orçamento e de proposta',
                                'Planilha de tabela de preços',
                                'Mensagens prontas para o assistente',
                                'Acesso por 1 ano, com atualizações',
                                'Pix ou cartão em até '.$maxInstallments.'x',
                            ] as $item)
                                <li class="flex gap-2.5">
                                    <x-heroicon-s-check class="mt-0.5 size-5 flex-none text-done"/>
                                    {{ $item }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="flex flex-col justify-center bg-[var(--tint)] p-8 text-center sm:p-12">
                        <p class="font-sans text-sm font-medium text-gray-500">Pagamento único</p>
                        <p class="mt-2 whitespace-nowrap font-sans text-4xl font-bold tracking-[-0.04em] text-[var(--ink)] sm:text-[2.75rem]">{{ $course->formattedPrice() }}</p>
                        <p class="mt-2 font-sans text-sm text-gray-500">no Pix ou em até {{ $maxInstallments }}x no cartão</p>
                        <a href="{{ $checkoutUrl }}"
                           class="mt-8 inline-flex justify-center rounded-full bg-brand-deep px-6 py-3.5 font-sans text-base font-semibold text-white shadow-md shadow-brand-deep/20 transition-colors hover:bg-[#0F3A57] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand">
                            Comprar o curso
                        </a>
                        <p class="mt-5 flex items-start justify-center gap-2 text-left font-sans text-[13px]/5 text-gray-500">
                            <x-heroicon-o-shield-check class="size-5 flex-none text-gray-400"/>
                            7 dias para pedir reembolso, sem precisar explicar o motivo.
                        </p>
                    </div>
                </div>
            </div>
        </x-container>
    </section>

    {{-- ═══ PERGUNTAS FREQUENTES ═══════════════════════════════════════ --}}
    <section class="pb-20 sm:pb-28">
        <x-container class="grid gap-x-16 gap-y-10 lg:grid-cols-[minmax(0,22rem)_1fr]">
            <div>
                <h2 class="font-sans text-3xl font-bold tracking-[-0.03em] text-[var(--ink)] sm:text-[2.75rem]/[1.1]">Perguntas frequentes</h2>
                <p class="mt-5 font-serif text-lg/8 text-gray-600">
                    Ficou alguma dúvida? <a href="{{ route('contact.show') }}" class="text-brand-deep underline underline-offset-4">Fale comigo</a>.
                </p>
            </div>
            <div class="faq divide-y divide-gray-200 border-y border-gray-200">
                @foreach($faq as [$question, $answer])
                    <details class="group py-6">
                        <summary class="flex items-start justify-between gap-6 font-sans text-lg font-semibold text-[var(--ink)]">
                            {{ $question }}
                            <x-heroicon-o-plus class="faq-icon mt-1 size-5 flex-none text-gray-400"/>
                        </summary>
                        <p class="mt-3 max-w-2xl font-serif text-lg/8 text-gray-600">{{ $answer }}</p>
                    </details>
                @endforeach
            </div>
        </x-container>
    </section>

    {{-- ═══ CHAMADA FINAL ══════════════════════════════════════════════ --}}
    <section class="px-4 pb-20 sm:px-6">
        <div class="mx-auto max-w-7xl overflow-hidden rounded-3xl bg-brand-deep">
            <div class="grid items-center gap-10 px-6 py-16 sm:px-12 sm:py-20 lg:grid-cols-[1fr_auto] lg:px-16">
                <div class="max-w-2xl">
                    <h2 class="font-sans text-3xl font-bold tracking-[-0.03em] text-white sm:text-5xl/[1.05]">
                        O próximo pedido de orçamento já pode sair assim
                    </h2>
                    <p class="mt-5 font-serif text-xl/8 text-white/80">
                        Uma mensagem sua, um PDF pronto e o cliente recebendo enquanto ainda está com o celular na mão.
                    </p>
                    <a href="{{ $checkoutUrl }}"
                       class="mt-9 inline-flex items-center rounded-full bg-white px-7 py-3.5 font-sans text-base font-semibold text-brand-deep transition-colors hover:bg-brand-light focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                        Comprar por {{ $course->formattedPrice() }}
                    </a>
                </div>
                <div class="hidden w-80 flex-col gap-1.5 rounded-2xl bg-[var(--chat-wall)] p-5 lg:flex" aria-hidden="true">
                    <x-offer.bubble from="them" time="09:31">Oi, tudo bem? Quanto fica uma limpeza pós-obra num apto de 80 m²?</x-offer.bubble>
                    <x-offer.bubble from="me" time="09:33" read>
                        <x-offer.pdf-file name="Orcamento-144.pdf"/>
                        Oi! Segue o orçamento 😊
                    </x-offer.bubble>
                </div>
            </div>
        </div>
    </section>

</div>
</x-layout>
