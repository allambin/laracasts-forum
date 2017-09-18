<?php

namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setUp();
        $this->thread = factory('App\Thread')->create();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testUserCanBrowseThreads()
    {
        $thread = factory('App\Thread')->create();
        $response = $this->get('/threads');
        $response->assertStatus(200);
        $response->assertSee($thread->title);
    }

    public function testUserCanReadOneThread()
    {
        $thread = factory('App\Thread')->create();
        $response = $this->get($thread->path());
        $response->assertSee($thread->title);
    }

    public function testUserCanReadRepliesAssociatedWithThread()
    {
        $reply = factory('App\Reply')->create([
            'thread_id' => $this->thread->id
        ]);

        $this->get($this->thread->path())
             ->assertSee($reply->body);
    }

    public function testThreadCanMakeStringPath()
    {
        $thread = create('App\Thread');
        $this->assertEquals('/threads/' . $thread->channel->slug . '/' . $thread->id, $thread->path());
    }

    public function testUserCanFilterThreadsAccordingToChannel()
    {
        $channel = create(Channel::class);
        $threadInChannel = create(Thread::class, [
            'channel_id' => $channel->id
        ]);
        $threadNotInChannel = create(Thread::class, [
            'channel_id' => function() {
                return create(Channel::class)->id;
            }
        ]);

        $this->get('threads/' . $channel->slug)
             ->assertSee($threadInChannel->title)
             ->assertDontSee($threadNotInChannel->title);
    }

    public function testUserCanFilterThreadsByUsername()
    {
        $user = factory(User::class)->create(['name' => 'JohnDoe']);
        $this->actingAs($user);
        $threadByJohnDoe = create(Thread::class, [
            'user_id' => auth()->id()
        ]);
        $threadNotByJohnDoe = create(Thread::class);

        $this->get('/threads?by=JohnDoe')
             ->assertSee($threadByJohnDoe->title)
             ->assertDontSee($threadNotByJohnDoe->title);
    }

    public function testUserCanFilterThreadsByPopularity()
    {
        $threadWithTwoReplies = create(Thread::class);
//        dd($threadWithTwoReplies);
        create(Reply::class, ['thread_id' => $threadWithTwoReplies->id], 2);
        $threadWithThreeReplies = create(Thread::class);
        create(Reply::class, ['thread_id' => $threadWithThreeReplies->id], 3);
//        $threadWithNoReplies = create(Thread::class);

        $response = $this->getJson('/threads?popular=1')->json(); // getJson to be sure the threads are returned in the right order
        $this->assertEquals([3, 2, 0], array_column($response, 'replies_count'));
    }
}
