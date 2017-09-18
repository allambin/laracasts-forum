<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParticipateInForumTest extends TestCase
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

    public function testAuthenticatedUserMayParticipateInForumThreads()
    {
        $user = factory('App\User')->create();
        $this->be($user);

        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply')->make();

        $this->post($thread->path() . '/replies', $reply->toArray());

        $this->get($thread->path())->assertSee($reply->body);
    }

    public function testUnauthenticatedUserMayNotParticipateInForumThreads()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->post('/threads/channel/1/replies', []);
        
    }

    public function testReplyRequireBody()
    {
        $this->actingAs(factory(User::class)->create());

        $thread = create(Thread::class);
        $reply = make(Reply::class, [
            'body' => null
        ]);

        $this->post($thread->path() . '/replies', $reply->toArray())
             ->assertSessionHasErrors('body');
    }
}
