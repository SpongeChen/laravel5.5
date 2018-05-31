<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
	// 允许更新内容
	protected $fillable = ['content'];

	// 一个动态模型 对应 一个用户模型
    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
