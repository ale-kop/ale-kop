<?php

use App\Jobs\ProcessNewsletterCampaign;
use App\Jobs\SendNewsletterEmail;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterList;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->general = NewsletterList::create(['name' => 'Newsletter', 'slug' => 'newsletter']);
    $this->design = NewsletterList::create(['name' => 'Skill de Design', 'slug' => 'skill-design']);
    $this->proposals = NewsletterList::create(['name' => 'Propostas Comerciais', 'slug' => 'propostas-comerciais']);

    $subscriber = fn (string $email, array $lists, string $status = 'active') => tap(
        NewsletterSubscriber::create(['email' => $email, 'status' => $status]),
        fn ($s) => $s->lists()->attach(collect($lists)->pluck('id')),
    );

    $subscriber('so-geral@gmail.com', [$this->general]);
    $subscriber('design@gmail.com', [$this->general, $this->design]);
    $subscriber('os-dois@gmail.com', [$this->general, $this->design, $this->proposals]);
    $subscriber('saiu@gmail.com', [$this->general, $this->design], 'unsubscribed');
    $subscriber('pendente@gmail.com', [$this->design], 'pending');

    $this->admin = User::factory()->create(['role' => 'admin']);
});

it('counts each active person once across the chosen lists', function () {
    $count = fn (array $lists) => $this->actingAs($this->admin)
        ->getJson(route('admin.newsletter.campaigns.audience', ['list_ids' => collect($lists)->pluck('id')->all()]))
        ->assertOk()
        ->json('count');

    expect($count([$this->general]))->toBe(3)
        ->and($count([$this->design]))->toBe(2)
        ->and($count([$this->design, $this->proposals]))->toBe(2)
        ->and($count([]))->toBe(0);
});

it('keeps the audience endpoint admin-only', function () {
    $this->getJson(route('admin.newsletter.campaigns.audience'))->assertUnauthorized();
    $this->actingAs(User::factory()->create(['role' => 'student']))->get(route('admin.newsletter.campaigns.audience'))->assertForbidden();
});

it('sends a segmented campaign only to the active people in its lists, once each', function () {
    Queue::fake();
    $campaign = NewsletterCampaign::create(['title' => 'Design', 'subject' => 'Oi', 'content' => '<p>x</p>', 'status' => 'scheduled', 'created_by' => $this->admin->id]);
    $campaign->lists()->sync([$this->design->id, $this->proposals->id]);

    (new ProcessNewsletterCampaign($campaign->fresh()))->handle();

    expect($campaign->fresh()->total_recipients)->toBe(2);
    Queue::assertPushed(SendNewsletterEmail::class, 2);
});

it('shows the reach and active counts on the campaign page before sending', function () {
    $campaign = NewsletterCampaign::create(['title' => 'Design', 'subject' => 'Oi', 'content' => '<p>x</p>', 'status' => 'draft', 'created_by' => $this->admin->id]);
    $campaign->lists()->sync([$this->design->id]);

    $this->actingAs($this->admin)->get(route('admin.newsletter.campaigns.show', $campaign))
        ->assertOk()
        ->assertSee('Vai para 2 pessoas');

    $this->actingAs($this->admin)->get(route('admin.newsletter.campaigns.create'))
        ->assertOk()
        ->assertSee('(2 ativos)', false)
        ->assertSee('(3 ativos)', false);
});
