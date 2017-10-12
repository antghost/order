<?php

namespace App\Http\Controllers\staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OrderTime;

class LimitController extends Controller
{
    public function index()
    {
        //早餐时限
        $breakfastTime = OrderTime::where('type', 1)->first();
        //午餐时限
        $lunchTime = OrderTime::where('type', 2)->first();
        //晚餐时限
        $dinnerTime = OrderTime::where('type', 3)->first();
        return view('staff.limit.index',[
            'breakfastTime' => $breakfastTime,
            'lunchTime' => $lunchTime,
            'dinnerTime' => $dinnerTime,
        ]);
    }

    public function store(Request $request)
    {
        //类型
        $type = $request->input('type');
        //开餐时间
        $bookTime = $request->input('book_time');
        //停餐时间
        $cancelTime = $request->input('cancel_time');

        $this->validate($request, [
            'type' => 'required|min:1|max:3|numeric',
            'book_time' => 'required',
            'cancel_time' => 'required',
        ]);

        $orderTime = OrderTime::updateOrCreate(
            ['type' => $type],
            ['book_time' => $bookTime, 'cancel_time' => $cancelTime]
        );

        if (isset($orderTime)){
            return redirect()->back()->with('status', '修改完成');
        } else {
            return redirect()->back()->withInput()->withErrors('提交失败');
        }

    }
}
