<?php

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function makeStandalonePost(array $tagExtra = []): Post
{
    $user = User::factory()->create(['name' => 'Aleksandr Kopelevitch']);
    $tag = Tag::create(['name' => 'Vendas', 'slug' => 'vendas', 'extra' => $tagExtra]);

    return Post::create([
        'name' => 'A pergunta que faço antes de qualquer proposta',
        'slug' => 'a-pergunta',
        'content' => '<p>'.str_repeat('palavra ', 450).'</p>',
        'meta' => ['description' => 'Uma pergunta simples encurta o ciclo.'],
        'tag_id' => $tag->id,
        'user_id' => $user->id,
    ]);
}

it('renders a standalone post in the reading layout', function () {
    $post = makeStandalonePost(['color' => '#6F8F5E']);

    $this->get(route('posts.show', $post->slug))
        ->assertOk()
        ->assertSee('class="article-title"', false)
        ->assertSee('class="html-content article-prose"', false)
        ->assertSee('--topic: #6F8F5E', false)
        ->assertSee('Uma pergunta simples encurta o ciclo.')
        ->assertSee('Aleksandr Kopelevitch')
        ->assertSee('3 min de leitura');
});

it('falls back to the brand color when the tag has no color', function () {
    $post = makeStandalonePost();

    $this->get(route('posts.show', $post->slug))
        ->assertOk()
        ->assertSee('--topic: '.Tag::DEFAULT_COLOR, false);
});

it('lists other standalone posts under "Continue lendo"', function () {
    $post = makeStandalonePost();
    Post::create(['name' => 'Follow-up sem parecer insistente', 'slug' => 'follow-up', 'content' => '<p>x</p>', 'tag_id' => $post->tag_id]);

    $this->get(route('posts.show', $post->slug))
        ->assertOk()
        ->assertSee('Continue lendo')
        ->assertSee('Follow-up sem parecer insistente');
});

it('estimates reading time at about 200 words per minute', function () {
    expect((new Post(['content' => '']))->readingMinutes())->toBe(1)
        ->and((new Post(['content' => '<p>'.str_repeat('a ', 401).'</p>']))->readingMinutes())->toBe(3);
});

it('validates the tag color as a hex value', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->post(route('tags.store'), ['name' => 'Livros', 'extra' => ['color' => 'roxo']])
        ->assertSessionHasErrors('extra.color');
});

it('shows a one-line breadcrumb from all articles to the post topic', function () {
    $post = makeStandalonePost();

    $this->get(route('posts.show', $post->slug))
        ->assertOk()
        ->assertSee('class="article-crumbs"', false)
        ->assertSeeInOrder([
            'href="'.route('index').'#posts"',
            'Artigos',
            'is-current',
            'href="'.route('posts.index', 'vendas').'"',
            'Vendas',
        ], false)
        ->assertDontSee('Voltar para os artigos');
});

it('renders the scroll-triggered subscribe popup on standalone posts', function () {
    $post = makeStandalonePost();

    $this->get(route('posts.show', $post->slug))
        ->assertOk()
        ->assertSee('data-article-popup', false)
        ->assertSee('id="form-article-subscribe"', false)
        ->assertSee('id="form-article-popup"', false)
        // cada formulário informa de onde veio o cadastro
        ->assertSee('name="source_form" value="post-end"', false)
        ->assertSee('name="source_form" value="post-popup"', false)
        ->assertSee('data-verify-url="'.route('newsletter.verify').'"', false);
});
