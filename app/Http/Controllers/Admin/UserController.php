<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Dept;
use App\Models\Userdept;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.user.index', ['users' => User::paginate(15), 'depts' => Dept::all()]);
    }

    public function create()
    {
        return view('admin.user.create', ['depts' => Dept::all()]);
    }

    //数据校验
    protected function validator(array $data)
    {
        return Validator::make($data ,[
            'username' => 'required|string|unique:users|max:255',
            'name' => 'required|string|max:255',
            'nickname' => 'required|string|unique:users|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    public function store(Request $request)
    {
        //闪存数据
        $request->flash();

        $this->validator($request->input())->validate();

        $user = User::create([
            'username' => $request->input('username'),
            'name' => $request->input('name'),
            'nickname' => $request->input('nickname'),
            'email' => $request->input('email'),
            'active' => 1,
            'is_admin' => 0,
            'dept_id' => $request->input('dept'),
            'password' => bcrypt($request->input('password')),
        ]);

        if(isset($user)){
            //写入中间表数据
//            Userdept::create([
//                'dept_id' => $request->input('dept'),
//                'user_id' => $user->id,
//            ]);

            return redirect('admin/user');
        } else {
            return redirect()->back()->withInput()->withErrors('提交失败');
        }
    }

    public function edit($id)
    {
        return view('admin.user.edit', ['user' => User::find($id), 'depts' => Dept::all()]);
    }

    public function update(Request $request, $id)
    {
        if($request->input('password') == '') {
            $this->validate($request, [
                'username' => 'required|string|unique:users,username,' . $id . '|max:255',
                'name' => 'required|string|max:255',
                'nickname' => 'required|string|unique:users,nickname,' . $id . '|max:255',
            ]);
        } else {
            $this->validate($request, [
                'username' => 'required|string|unique:users,username,' . $id . '|max:255',
                'name' => 'required|string|max:255',
                'nickname' => 'required|string|unique:users,nickname,' . $id . '|max:255',
                'password' => 'string|min:6|confirmed',
            ]);
        }

        $user = User::findOrFail($id);
        $user->username = $request->input('username');
        $user->name = $request->input('name');
        $user->nickname = $request->input('nickname');
        $user->email = $request->input('email');
        if (!empty($request->input('password'))){
            $user->password = bcrypt($request->input('password'));
        }

        if($user->save()){
            return redirect()->back();
        } else {
            return redirect()->back()->withInput()->withErrors('更新失败', 'msg');
        }
    }

    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->back();
    }
}
