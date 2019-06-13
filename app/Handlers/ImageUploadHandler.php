<?php

namespace App\Handlers;

use Image;

class ImageUploadHandler
{
    //只允许以下后缀的图片文件上传
    protected $allowed_ext = ["png", "jpg", "gif", "jpeg"];
    
    public function save($file, $folder, $file_prefix, $max_width = false)
    {
        
        // 构建存储的文件夹规则, 值如: uploads/images/avatars/201906/13/
        // 文件夹切割能让查找效率更高
        $folder_name = "uploads/images/{$folder}/" . date('Ym/d', time());
        
        // 文件具体存储的物理路径， public_path() 获取的是 public 文件夹的物理路径
        // 值如： /home/vagrant/code/larabs/public/uploads/images/avatars/201906/13/
        $upload_path = public_path() . '/' . $folder_name;
        
        // 获取文件的后缀名, 因为图片从剪贴板连跌时后缀名为空, 所以此处确保后缀一直存在
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';
        
        // 拼接文件名, 加前缀是为了增加辨析度, 前缀可以是相关数据的 ID
        // for example: 1_1299349945.png
        $filename = $file_prefix . '_' . time() . '_' . str_random(10) . '.' . $extension;
        
        // 如果上传的不是允许的图片类型将终止操作
        if (!in_array($extension, $this->allowed_ext)) {
            return false;
        }
        
        // 将图片移到我们的目标存储路径中
        $file->move($upload_path, $filename);
        
        if ($max_width && $extension != 'gif') {
            $this->reduceSize($upload_path . '/' . $filename, $max_width);
        }
        return [
            'path' => config('app.url') . "/{$folder_name}/{$filename}"
        ];
    }
    
    private function reduceSize($file_path, int $max_width)
    {
        // 1. reading images
        $image = Image::make($file_path);
        
        // 进行图片大小尺寸调整
        $image->resize($max_width, null, function ($constraint) {
            
            // 设定宽度是 max_width, 高度双方等比例缩放
            $constraint->aspectRatio();
            
            // 防止裁图时图片尺寸变大
            $constraint->upsize();
        });
        
        // outputting images
        $image->save();
    }
    
}

