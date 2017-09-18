<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
//    protected $fillable = ['body'];
    protected $guarded = [];

    // every time we get a Thread, we will get the replies_count atttribute with it.
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('replyCount', function($builder) {
            $builder->withCount('replies');
        });
    }

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
//        return route('threads.show', $this);
    }

    public function replies()
    {
        return $this->hasMany('App\Reply');
    }

    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo('App\Channel');
    }

    public function addReply($reply)
    {
        $this->replies()->create($reply);
    }

//    public function getReplyCountAttribute()
//    {
//        return $this->replies()->count();
//    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
}
