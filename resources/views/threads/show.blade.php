@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ $thread->owner->name }} said: {{ $thread->title }}
                    </div>
                    <div class="panel-body">
                        {{ $thread->body }}
                    </div>
                </div>

                @foreach($replies as $reply)
                    @include('threads._reply')
                @endforeach

                {{ $replies->links() }}

                @if(auth()->check())
                    <form action="{{ $thread->path() . '/replies' }}" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group">
                    <textarea name="body" id="body" class="form-control" placeholder="Have something to say?"
                              rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-default" type="submit" value="Post"/>
                        </div>
                    </form>
                @else
                    <p>Please <a href="{{ route('login') }}">sign in</a> to participate</p>
                @endif
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        This thread was published {{ $thread->created_at->diffForHumans()}}
                        by {{ $thread->owner->name }} and has currently {{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}.
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
