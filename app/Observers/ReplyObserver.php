<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\TopicReplied;
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
        $reply->updateReplyCount($reply);
        
        // 通知话题作者又新的评论
        $reply->topic->user->notify(new TopicReplied($reply));
    }

    public function deleted(Reply $reply)
    {
        $reply->updateReplyCount($reply);
    }
}
