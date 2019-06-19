<?php

namespace App\Models;

class Topic extends Model
{
    protected $fillable = [
        'title',
        'body',
        'category_id',
        'excerpt',
        'slug'
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function scopeWithOrder($query, $order)
    {
        // 不通的排序, 使用不通的数据读取逻辑
        switch ($order) {
            case 'recent':
                $query->recent();
                break;
                
            default:
                $query->recentReplied();
                break;
        }
        
        // 预加载防止 N+1 问题
        return $query->with('user', 'category');
    }
    
    public function scopeRecent($query)
    {
        // 按照创建时间排序
        return $query->orderBy('created_at', 'desc');
    }
    
    public function scopeRecentReplied($query)
    {
        // 当话题有心得回复时, 我们将重新编写逻辑更新话题模型 reply_cout
        // 此时会自动触发框架对数据模型 updated_at 时间戳更新
        return $query->orderBy('updated_at', 'desc');
    }
    
    public function link($params = [])
    {
        return route('topics.show', array_merge([$this->id, $this->slug], $params));
    }
}
