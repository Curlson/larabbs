<?php

namespace App\Observers;

use App\Handlers\SlugTranslateHandler;
use App\Jobs\TranslateSlug;
use App\Models\Topic;
use Illuminate\Support\Facades\DB;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function saving(Topic $topic)
    {
        // XSS 过滤
        $topic->body = clean($topic->body, 'user_topic_body');
        
        // 生成话题摘要
        $topic->excerpt = make_excerpt($topic->body);
    }
    
    public function saved(Topic $topic)
    {
        // slug 翻译
        if (!$topic->slug) {
            
            // 推送任务到队列
            dispatch(new TranslateSlug($topic));
        }
    }
    
    public function deleted(Topic $topic)
    {
        // 删除话题的所有回复
        DB::table('replies')->where('topic_id', $topic->id)->delete();
    }
}
