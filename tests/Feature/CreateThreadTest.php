<?php

namespace Tests\Feature;

use App\Channel;
use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateThreadTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testAuthenticatedUserCanCreateThread()
    {
//        $user = factory('App\User')->create();
        $this->actingAs(create('App\User'));
//        $thread = factory('App\Thread')->raw();
        $thread = factory('App\Thread')->make();

        $response = $this->post('threads', $thread->toArray());

        $this->get($response->headers->get('Location'))
             ->assertSee($thread->title);
    }

    public function testGuestCannotCreateThread()
    {
        $this->setExpectedException('Illuminate\Auth\AuthenticationException');
        $thread = make('App\Thread');

        $this->post('threads', $thread->toArray());

    }

    // needs to change the Handler file for this one
//    public function testGuestCannotSeeTheCreateFormPage()
//    {
//        $this->get('/threads/create')
//             ->assertRedirect('/login');
//
//    }

    public function testThreadRequireTitle()
    {
//        $this->actingAs(factory(User::class)->create());
//
//        $thread = make(Thread::class, [
//            'title' => null
//        ]);
//
//        $this->post('/threads', $thread->toArray())
//             ->assertSessionHasErrors('title');

        $this->publishThread(['title' => null])
             ->assertSessionHasErrors('title');
    }

    public function testThreadRequireBody()
    {
        $this->publishThread(['body' => null])
             ->assertSessionHasErrors('body');
    }

    public function testThreadRequireValidChannelId()
    {
        $this->publishThread(['channel_id' => null])
             ->assertSessionHasErrors('channel_id');
    }
    public function testThreadRequireExistingChannelId()
    {
        factory(Channel::class)->create();

        $this->publishThread(['channel_id' => 99])
             ->assertSessionHasErrors('channel_id');
    }

    private function publishThread($array)
    {
        $this->actingAs(factory(User::class)->create());

        $thread = make(Thread::class, $array);

        return $this->post('/threads', $thread->toArray());
    }
}
