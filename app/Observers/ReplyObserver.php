<?php

namespace App\Observers;

use App\Models\Reply;
use HTMLPurifier;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    
    public function creating(Reply $reply)
    {
        $reply->content = clean($reply->content, 'user_topic_body');
    }
    
    public function created(Reply $reply)
    {
        // 增加 topic 对应的回复数量
        $reply->topic->reply_count = $reply->topic->replies->count();
        $reply->topic->save();
    }

    public function updating(Reply $reply)
    {
        //
    }
}
