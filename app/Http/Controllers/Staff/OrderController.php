<?php

namespace App\Http\Controllers\staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Dept;
use App\User;
use App\Models\UserOrderStatus;

class OrderController extends Controller
{
    /**
     * 首页
     */
    public function index()
    {
        return view('staff.order.index', [ 'depts' => Dept::all(), 'users' => User::paginate(15)]);
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

        return view('staff.order.index', ['users' => $users, 'depts' => Dept::all()]);
    }

    /**
     *显示编辑页面
     */
    public function edit($id)
    {
        return view('staff.order.edit', ['user' => User::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        //早餐状态
        $breakfast = $request->input('breakfast_chk') ? 1 : 0;
        $breakfastBeginDate = $request->input('breakfast_begin_date');
        //午餐状态
        $lunch = $request->input('lunch_chk') ? 1 : 0;
        $lunchBeginDate = $request->input('lunch_begin_date');
        //晚餐状态
        $dinner = $request->input('dinner_chk') ? 1 : 0;
        $dinnerBeginDate = $request->input('dinner_begin_date');

        $user = User::findOrFail($id);

        //早餐停开餐处理
        if ($breakfast) {
            dd($breakfastBeginDate);
        } else {
            dd('stop');
        }

        $userOrderStatuses = UserOrderStatus::updateOrCreate(
            //第一个参数数组中必须是unique,否则更新将失效
            ['user_id' => $id],
            //第二个参数为更新/创建的值
            ['breakfast' => $breakfast, 'lunch' => $lunch, 'dinner' => $dinner]
            );

        dd($userOrderStatuses);
    }

}
