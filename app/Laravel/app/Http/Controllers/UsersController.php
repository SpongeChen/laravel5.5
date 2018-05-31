<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Mail, Auth;


class UsersController extends Controller
{
	public function __construct()
	{
		// 中间件身份验证
		$this->middleware('auth', [
			'except' => ['show', 'create', 'store', 'index', 'confirmEmail']
		]);
		$this->middleware('guest', [
            'only' => ['create']
        ]);
	}

	// 用户列表
	public function index()
	{
		$users = User::paginate(10);
		return view('users.index', compact('users'));
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
            return view ('users.edit', compact('user'));
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    	return view('users.edit', compact('user'));
    }

    // 展示页
    public function show(User $user)
    {
        $statuses = $user->statuses()
                           ->orderBy('created_at', 'desc')
                           ->paginate(30);
        return view('users.show', compact('user', 'statuses'));
    }

    // 注册入库
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Auth::login($user);
        // session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        // return redirect()->route('users.show', [$user]);

        // 修改为发邮件确认
        $this->sendEmailConfirmationTo($user);
        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
        return redirect('/');
    }

    // 注册发送邮件
    protected function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $from = 'aufree@yousails.com';
        $name = 'Aufree';
        $to = $user->email;
        $subject = "感谢注册 Sample 应用！请确认你的邮箱。";
        Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }

    // 确认邮件
    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrfail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();
        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
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

    // 删除用户
    public function destroy(User $user)
    {
    	$this->authorize('destroy', $user);
    	$user->delete();
    	session()->flash('success', '成功删除用户！');
    	return back();
    }


}
