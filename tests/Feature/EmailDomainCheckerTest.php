<?php

use App\Services\EmailDomainChecker;
use Illuminate\Support\Facades\Cache;

/** Checker com DNS roteirizado: cada chamada a hasRecords() consome a próxima resposta. */
function scriptedChecker(array $answers): EmailDomainChecker
{
    return new class($answers) extends EmailDomainChecker
    {
        public array $asked = [];

        public function __construct(private array $answers) {}

        protected function hasRecords(string $host): bool
        {
            $this->asked[] = $host;

            return array_shift($this->answers) ?? false;
        }

        protected function pauseBeforeRetry(): void {}
    };
}

beforeEach(fn () => Cache::flush());

it('accepts a domain that resolves right away', function () {
    $checker = scriptedChecker([true]);

    expect($checker->isAcceptable('exemplo.com'))->toBeTrue()
        ->and($checker->asked)->toBe(['exemplo.com']);
});

it('does not reject a valid domain on a transient lookup failure (first attempt fails, retry works)', function () {
    $checker = scriptedChecker([false, true]);

    expect($checker->isAcceptable('exemplo.com'))->toBeTrue()
        ->and($checker->asked)->toBe(['exemplo.com', 'exemplo.com']);
});

it('rejects a domain that does not exist while DNS itself is healthy', function () {
    // domínio falha duas vezes; o domínio-canário resolve
    $checker = scriptedChecker([false, false, true]);

    expect($checker->isAcceptable('dominio-inexistente.test'))->toBeFalse()
        ->and($checker->asked)->toBe(['dominio-inexistente.test', 'dominio-inexistente.test', EmailDomainChecker::CANARY_DOMAIN]);
});

it('accepts the address when DNS itself is down (not even the canary resolves)', function () {
    $checker = scriptedChecker([false, false, false]);

    expect($checker->isAcceptable('exemplo.com'))->toBeTrue();
});

it('caches successful lookups so the next attempt does not hit DNS', function () {
    scriptedChecker([true])->isAcceptable('exemplo.com');

    $second = scriptedChecker([]);
    expect($second->isAcceptable('Exemplo.com'))->toBeTrue()
        ->and($second->asked)->toBe([]);
});

it('does not cache failures', function () {
    scriptedChecker([false, false, true])->isAcceptable('exemplo.com'); // rejeita

    expect(scriptedChecker([true])->isAcceptable('exemplo.com'))->toBeTrue();
});

it('rejects an empty domain', function () {
    expect(scriptedChecker([])->isAcceptable(''))->toBeFalse();
});
