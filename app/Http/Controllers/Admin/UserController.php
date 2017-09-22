<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\User;
use App\Models\Dept;
use App\Models\Price;
use App\Models\PriceUser;

class UserController extends Controller
{
    /**
     * 显示用户列表页面
     */
    public function index()
    {
        return view('admin.user.index', ['users' => User::paginate(15), 'depts' => Dept::all()]);
    }

    /**
     * 新增用户页面
     */
    public function create()
    {
        return view('admin.user.create', ['depts' => Dept::all(), 'prices' => Price::all()]);
    }

    /**
     * 数据校验
     */
    protected function validator(array $data)
    {
        return Validator::make($data ,[
            'username' => 'required|string|unique:users|max:255',
            'name' => 'required|string|max:255',
            'nickname' => 'required|string|unique:users|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * 新增用户数据
     */
    public function store(Request $request)
    {
        //闪存数据
        $request->flashExcept('password');

        $this->validator($request->input())->validate();

        //需要增加事务，确保用户与对应的标准同时添加成功
        DB::beginTransaction();
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
            //对应收费标准数据
            $price = Price::findOrFail($request->input('price'));
            $breakfast = $price->breakfast;
            $lunch = $price->lunch;
            $dinner = $price->dinner;
            //写入人员收费标准历史表数据
            $priceUser = PriceUser::create([
                //开始日期默认为第二天
                'begin_date' => Carbon::tomorrow(),
                'user_id' => $user->id,
                'price_id' => $request->input('price'),
                'breakfast' => $breakfast,
                'lunch' => $lunch,
                'dinner' => $dinner,
            ]);
            if (isset($priceUser)){
                DB::commit();
                return redirect('admin/user');
            } else {
                DB::rollBack();
            }

        } else {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors('提交失败');
        }
    }

    /**
     * 显示用户编辑页面
     */
    public function edit($id)
    {
        return view('admin.user.edit', ['user' => User::find($id), 'depts' => Dept::all()]);
    }

    /**
     * 更新用户数据
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'username' => 'required|string|unique:users,username,' . $id . '|max:255',
            'name' => 'required|string|max:255',
            'nickname' => 'required|string|unique:users,nickname,' . $id . '|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'email' => 'required|email|unique:users,email,' . $id . '|max:255',
        ]);

        $user = User::findOrFail($id);
        $user->username = $request->input('username');
        $user->name = $request->input('name');
        $user->nickname = $request->input('nickname');
        $user->email = $request->input('email');
        $user->dept_id = $request->input('dept');
        //密码不为空时修改为新密码
        if (!empty($request->input('password'))){
            $user->password = bcrypt($request->input('password'));
        }

        if($user->save()){
            return redirect()->back()->with('status', '更新成功！');
        } else {
            return redirect()->back()->withInput()->withErrors('更新失败', 'msg');
        }
    }

    /**
     * 删除用户
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            //删除对应的餐费标准历史
            User::findOrFail($id)->priceUsers()->delete();
            User::destroy($id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect()->back();
    }

    /**
     * 用户搜索功能
     */
    public function search(Request $request)
    {
        $request->flash();
        $dept_id = $request->input('dept');
        $name = $request->input('name');

        //查询条件
        $users = User::when($dept_id, function ($query) use ($dept_id){
            return $query->whereHas('dept', function ($query) use ($dept_id){
                $query->where('id', $dept_id);
            });
        })->when($name, function ($query) use ($name){
            return $query->where('name', 'like', '%'.$name.'%');
        })->paginate(15);

        //分页查询参数
        $users = $users->appends([
            'name' => $name,
            'dept' => $dept_id,
        ]);

        return view('admin.user.index', ['users' => $users, 'depts' => Dept::all()]);
    }

}
