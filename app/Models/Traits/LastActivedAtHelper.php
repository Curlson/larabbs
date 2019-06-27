<?php

namespace App\Models\Traits;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;

trait LastActivedAtHelper
{
    // 缓存相关
    protected $hash_prefix = 'larabbs_last_actived_at_';
    protected $field_prefix = 'user_';
    
    /**
     *
     */
    public function recordLastActivedAt()
    {
        // 获取今天的日期
        $date = Carbon::now()->toDateString();
        
        // Redis 哈希表的命名, 如: larabbs_last_actived_at_2017-10-21
        $hash = $this->getHashFromDateString($date);
        
        // 字段名称, 如: user_1
        $field = $this->getHashField();
        
        // 当前时间, 如: 2019-06-27 11:41:17
        $now = Carbon::now()->toDateTimeString();
        
        // 数据写入 Redis, 字段已存在会被更新
        Redis::hSet($hash, $field, $now);
    }
    
    public function syncUserActivedAt()
    {
        // 获取昨日的日期, 格式如: 2019-06-27
        $yesterday_date = Carbon::yesterday()->toDateString();
        
        // Redis 哈希表的命名, 如: larabbs_last_actived_at_2019-6-27
        $hash = $this->getHashFromDateString($yesterday_date);
        
        // 从 Redis 中取出所有哈希表李的数据
        $dates = Redis::hGetAll($hash);
        
        // 遍历, 并同步到数据库中
        foreach ($dates as $user_id => $actived_at) {
            // 会将 `user_1` 转换为 1
            $user_id = str_replace($this->field_prefix, '', $user_id);
            
            // 只有当用户存在时才更新到数据库中
            /* @var User $user */
            if($user = $this->find($user_id)) {
                $user->last_actived_at = $actived_at;
                $user->save();
            }
        }
        
        // 以数据库为中心的存储, 既可删除
        Redis::del($hash);
    }
    
    /**
     * @param $value
     * @return Carbon
     */
    public function getLastActivedAtAttribute($value)
    {
        // 获取今天的日期
        $date = Carbon::now()->toDateString();
        
        // Redis 哈希表的命名,如: larabbs_last_actived_at_2019-06-27
        $hash = $this->getHashFromDateString($date);
        $field = $this->getHashField();
    
        // 三元运算符, 悠闲选择 Redis 的数据,否则使用数据库中
        $datetime = Redis::hGet($hash, $field) ? : $value;
        
        // 如果存在的话, 返回时间对应的 Carbon 实体
        if($datetime) {
            return Carbon::now();//new Carbon($datetime);
        } else {
            // 否则使用用户注册事件
            return $this->created_at;
        }
    }
    
    /**
     * @return string
     */
    public function getHashField(): string
    {
        // 字段名称: 如 user_1
        $field = $this->field_prefix . $this->id;
        return $field;
    }
    
    /**
     * @param $date
     * @return string
     */
    public function getHashFromDateString($date): string
    {
        $hash = $this->hash_prefix . $date;
        return $hash;
    }
}
