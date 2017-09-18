<div class="panel panel-default">
    <div class="panel-heading">
        {{ $reply->owner->name }} said {{ $thread->created_at->diffForHumans() }}
    </div>
    <div class="panel-body">
        <div class="body">
            {{ $reply->body }}
        </div>
    </div>
</div>