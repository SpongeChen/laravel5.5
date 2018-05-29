<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
	public function __construct()
	{
		// 中间件身份验证
		$this->middleware('auth', [
			'except' => ['show', 'create', 'store']
		]);
		$this->middleware('guest', [
            'only' => ['create']
        ]);
	}
	// 注册页
	public function create()
    {
        return view('users.create');
    }

    // 编辑页
    public function edit(User $user)
    {
    	try {
            $this->authorize ('update', $user);
            return view ('users.edit', compact ('user'));
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    	return view('users.edit', compact('user'));
    }

    // 展示页
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    // 注册入库
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $this->authorize('update', $user);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        Auth::login($user);
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');

        return redirect()->route('users.show', [$user]);
    }

    // 编辑入库
    public function update(User $user, Request $request)
    {
    	$this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);

        $data = [];
        $data['name'] = $request->name;
        if ( $request->password ) {
        	$data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success', '个人资料更新成功！');

        return redirect()->route('users.show', $user->id);
    }


}
