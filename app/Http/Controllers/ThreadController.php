<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    use DatabaseMigrations;

    /**
     * ThreadController constructor.
     */
    public function __construct()
    {
//        $this->middleware('auth')->only(['create', 'store']);
        $this->middleware('auth')->except(['index', 'show']);
    }


    /**
     * Display a listing of the resource.
     *
     * @param Channel $channel
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::latest()->filter($filters);
        if($channel->exists) {
//            $channelId = Channel::where('slug', $channelSlug)->first()->id;
//            $threads = Thread::where('channel_id', $channelId)->latest()->get();
            //$threads = $channel->threads()->latest()/*->get()*/;
            $threads->where('channel_id', $channel->id);
        } else {
            //$threads = Thread::latest()/*->get()*/;
        }
//
//        if($username = request('by')) {
//            $user = User::where('name', $username)->firstOrFail();
//            $threads->where('user_id', $user->id);
//        }
//
//        dd($threads->toSql());

        $threads = $threads->get();

        if(request()->wantsJson()) {
            return $threads;
        }

        return view('threads.index', compact('threads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'channel_id' => 'required|exists:channels,id'
        ]);
        $thread = Thread::create([
            'title' => request('title'),
            'body' => request('body'),
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id')
        ]);

        return redirect($thread->path());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channelId, Thread $thread)
    {
//        $thread->load('replies'); // eager loading
//        Thread::withCount('replies')->find($thread->id);
//        $thread->replyCount; // with a dynamic attribute on the Thread model

        return view('threads.show', [
            'thread' => $thread,
            'replies' => $thread->replies()->paginate(1)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Thread $thread)
    {
        //
    }
}
