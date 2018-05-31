<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
use Auth;

class StatusesController extends Controller
{
	public function __construct()
    {
    	// 判断登陆状态
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
    	$this->validate($request, [
    		'content' => 'required|max:140'
    	]);

    	// Auth::user() 方法我们可以获取到当前用户实例
    	Auth::user()->statuses()->create([
            'content' => $request['content']
        ]);
        return redirect()->back();
    }

    public function destroy(Status $status)
    {
        $this->authorize('destroy', $status);
        $status->delete();
        session()->flash('success', '微博已被成功删除！');
        return redirect()->back();
    }

}
