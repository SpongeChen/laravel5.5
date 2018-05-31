<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class FollowersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $user = $users->first();
        $user_id = $user->id;

        // 获取去掉ID为1的所有用户ID数组
        $followers = $users->slice(1);
        $follower_ids = $followers->pluck('id')->toArray();

        // 让1号关注其他用户
        $user->follow($follower_ids);

        // 其他用户关注1号
        foreach ($followers as $follower) {
        	$follower->follow($user_id);
        }
    }
}
