<?php

namespace Tests\Feature\Http\Controllers\Anonymous\Users;

use template\Domain\Users\Leads\Events\LeadCreatedEvent;
use template\Domain\Users\Leads\Lead;
use template\Domain\Users\Leads\Notifications\HandshakeMailToConfirmReceptionToSender;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\OAuthTestCaseTrait;
use Tests\TestCase;

class LeadsControllerTest extends TestCase
{
    use OAuthTestCaseTrait;
    use DatabaseMigrations;

    public function testIndex()
    {
        $this
            ->get('/contact')
            ->assertSuccessful()
            ->assertSeeText('Contact');
    }

    public function testStoreWithEmptyForm()
    {
        $lead = factory(Lead::class)->raw();
        Event::fake();
        Notification::fake();
        $this
            ->followingRedirects()
            ->from('/contact')
            ->post('/contact', [
                'civility' => $lead['civility'],
                'first_name' => '',
                'last_name' => '',
                'email' => '',
                'subject' => '',
                'message' => '',
                'certify' => '',
            ])
            ->assertSuccessful()
            ->assertSeeText('The first name field is required.')
            ->assertSeeText('The last name field is required.')
            ->assertSeeText('The email field is required.')
            ->assertSeeText('The subject field is required.')
            ->assertSeeText('The message field is required.');
        Event::assertNotDispatched(LeadCreatedEvent::class);
        Notification::assertTimesSent(0, HandshakeMailToConfirmReceptionToSender::class);
        $this->assertDatabaseMissing('users_leads', $lead);
    }

    public function testStoreWithInvalidEmail()
    {
        $lead = factory(Lead::class)->raw();
        Event::fake();
        Notification::fake();
        $this
            ->followingRedirects()
            ->from('/contact')
            ->post('/contact', [
                'civility' => $lead['civility'],
                'first_name' => $lead['first_name'],
                'last_name' => $lead['last_name'],
                'email' => $this->faker->text,
                'subject' => $this->faker->text,
                'message' => $this->faker->text,
                'certify' => true,
            ])
            ->assertSuccessful()
            ->assertSeeText('The email must be a valid email address.');
        Event::assertNotDispatched(LeadCreatedEvent::class);
        Notification::assertTimesSent(0, HandshakeMailToConfirmReceptionToSender::class);
        $this->assertDatabaseMissing('users_leads', $lead);
    }

    public function testStoreWithAnonymous()
    {
        $lead = factory(Lead::class)->raw();
        Event::fake();
        Notification::fake();
        $this
            ->from('/contact')
            ->post('/contact', [
                'civility' => $lead['civility'],
                'first_name' => $lead['first_name'],
                'last_name' => $lead['last_name'],
                'email' => $lead['email'],
                'subject' => $this->faker->text,
                'message' => $this->faker->text,
                'certify' => true,
            ])
            ->assertRedirect('/contact');
        Event::assertDispatched(LeadCreatedEvent::class);
        Notification::assertTimesSent(1, HandshakeMailToConfirmReceptionToSender::class);
        $this->assertDatabaseHas('users_leads', $lead);
    }

    public function testStoreWithAdministrator()
    {
        $user = $this->actingAsAdministrator();
        $lead = factory(Lead::class)->create([
            'user_id' => $user->id,
            'civility' => $user->civility,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
        ]);
        Event::fake();
        Notification::fake();
        $this
            ->assertAuthenticated()
            ->from('/contact')
            ->post('/contact', [
                'civility' => $lead['civility'],
                'first_name' => $lead['first_name'],
                'last_name' => $lead['last_name'],
                'email' => $lead['email'],
                'subject' => $this->faker->text,
                'message' => $this->faker->text,
                'certify' => true,
            ])
            ->assertRedirect('/contact');
        Event::assertNotDispatched(LeadCreatedEvent::class);
        Notification::assertTimesSent(1, HandshakeMailToConfirmReceptionToSender::class);
    }

    public function testStoreWithCustomer()
    {
        $user = $this->actingAsCustomer();
        $lead = factory(Lead::class)->create([
            'user_id' => $user->id,
            'civility' => $user->civility,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
        ]);
        Event::fake();
        Notification::fake();
        $this
            ->assertAuthenticated()
            ->from('/contact')
            ->post('/contact', [
                'civility' => $lead['civility'],
                'first_name' => $lead['first_name'],
                'last_name' => $lead['last_name'],
                'email' => $lead['email'],
                'subject' => $this->faker->text,
                'message' => $this->faker->text,
                'certify' => true,
            ])
            ->assertRedirect('/contact');
        Event::assertNotDispatched(LeadCreatedEvent::class);
        Notification::assertTimesSent(1, HandshakeMailToConfirmReceptionToSender::class);
    }
}
