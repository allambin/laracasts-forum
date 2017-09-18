<?php

namespace Tests\Unit;

use App\Channel;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ChannelTest extends TestCase
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

    public function testChannelHasThreads()
    {
        $channel = create(Channel::class);
        $thread = create(Thread::class, [
            'channel_id' => $channel->id
        ]);

        $this->assertTrue($channel->threads->contains($thread));
    }
}
