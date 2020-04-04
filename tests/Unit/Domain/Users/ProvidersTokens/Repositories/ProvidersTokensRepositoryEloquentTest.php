<?php

namespace Tests\Unit\Domain\Users\ProvidersTokens\Repositories;

use template\Domain\Users\ProvidersTokens\Events\ProviderTokenCreatedEvent;
use template\Domain\Users\ProvidersTokens\Events\ProviderTokenDeletedEvent;
use template\Domain\Users\ProvidersTokens\Events\ProviderTokenUpdatedEvent;
use template\Domain\Users\ProvidersTokens\ProviderToken;
use template\Domain\Users\Users\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use template\Domain\Users\ProvidersTokens\Repositories\ProvidersTokensRepositoryEloquent;

class ProvidersTokensRepositoryEloquentTest extends TestCase
{
    use DatabaseMigrations;

    protected $r_providers_tokens = null;

    public function __construct()
    {
        parent::__construct();

        $this->r_providers_tokens = app()->make(ProvidersTokensRepositoryEloquent::class);
    }

    public function testCheckIfRepositoryIsCorrectlyInstantiated()
    {
        $this->assertTrue($this->r_providers_tokens instanceof ProvidersTokensRepositoryEloquent);
    }

    public function testModel()
    {
        $this->assertEquals(ProviderToken::class, $this->r_providers_tokens->model());
    }

    public function testCreate()
    {
        $user = factory(User::class)->create();
        $providerToken = factory(ProviderToken::class)->raw(['user_id' => $user->id]);
        Event::fake();
        $providerToken = $this->r_providers_tokens->create($providerToken);
        Event::assertDispatched(ProviderTokenCreatedEvent::class, function ($event) use ($providerToken) {
            return $event->provider_token->id === $providerToken->id;
        });
        $this->assertDatabaseHas('users_providers_tokens', $providerToken->toArray());
    }

    public function testUpdate()
    {
        $user = factory(User::class)->create();
        $providerToken = factory(ProviderToken::class)->create(['user_id' => $user->id]);
        $newProviderToken = factory(ProviderToken::class)->raw(['user_id' => $user->id]);
        Event::fake();
        $providerToken = $this->r_providers_tokens->update($newProviderToken, $providerToken->id);
        Event::assertDispatched(ProviderTokenUpdatedEvent::class, function ($event) use ($providerToken) {
            return $event->provider_token->id === $providerToken->id;
        });
        $providerTokenArr = $providerToken->toArray();
        Arr::forget($providerTokenArr, 'updated_at');
        $this->assertDatabaseHas('users_providers_tokens', $providerTokenArr);
    }

    public function testDelete()
    {
        $user = factory(User::class)->create();
        $providerToken = factory(ProviderToken::class)->create(['user_id' => $user->id]);
        Event::fake();
        $providerToken = $this->r_providers_tokens->delete($providerToken->id);
        Event::assertDispatched(ProviderTokenDeletedEvent::class, function ($event) use ($providerToken) {
            return $event->provider_token->id === $providerToken->id;
        });
        $this->assertDatabaseMissing('users_providers_tokens', $providerToken->toArray());
    }

    public function testGetProviders()
    {
        $this->assertEquals(ProviderToken::PROVIDERS, $this->r_providers_tokens->getProviders()->toArray());
    }

    public function testFilterByProvider()
    {
        $user = factory(User::class)->create();
        factory(ProviderToken::class)->state(ProviderToken::GOOGLE)->create(['user_id' => $user->id]);
        $providerToken = factory(ProviderToken::class)->state(ProviderToken::TWITTER)->create(['user_id' => $user->id]);
        factory(ProviderToken::class)->state(ProviderToken::LINKEDIN)->create(['user_id' => $user->id]);
        $repositoryProviderToken = $this
            ->r_providers_tokens
            ->skipPresenter()
            ->filterByProvider($providerToken->provider_id, $providerToken->provider)
            ->get();
        $this->assertEquals(1, $repositoryProviderToken->count());
    }

    public function testSaveUserTokenForProvider()
    {
        $user = factory(User::class)->create();
        $providerToken = factory(ProviderToken::class)->raw();
        Event::fake();
        $providerToken = $this
            ->r_providers_tokens
            ->saveUserTokenForProvider(
                $user,
                $providerToken['provider'],
                $providerToken['provider_id'],
                $providerToken['provider_token']
            );
        Event::assertDispatched(ProviderTokenUpdatedEvent::class, function ($event) use ($providerToken) {
            return $event->provider_token->id === $providerToken->id;
        });
        $this->assertDatabaseHas('users_providers_tokens', $providerToken->toArray());
    }

    public function testCheckIfTokenIsAvailableForUser()
    {
        $user = factory(User::class)->create();
        $providerToken = factory(ProviderToken::class)->create(['user_id' => $user->id]);
        $providerToken = $this
            ->r_providers_tokens
            ->checkIfTokenIsAvailableForUser(
                $user,
                $providerToken['provider_id'],
                $providerToken['provider']
            );
        $this->assertTrue($providerToken);
    }

    public function testFindUserForProvider()
    {
        $user = factory(User::class)->create();
        $providerToken = factory(ProviderToken::class)->create(['user_id' => $user->id]);
        $providerToken = $this
            ->r_providers_tokens
            ->findUserForProvider(
                $providerToken['provider_id'],
                $providerToken['provider']
            );
        $this->assertDatabaseHas('users_providers_tokens', $providerToken->toArray());
    }
}
