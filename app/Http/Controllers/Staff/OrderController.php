<?php

namespace App\Http\Controllers\staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Dept;
use App\User;

class OrderController extends Controller
{
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
}
