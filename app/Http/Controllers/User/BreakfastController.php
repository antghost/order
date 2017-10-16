<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\BookBreakfast;
use App\Models\CancelBreakfast;

class BreakfastController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('user.breakfast.index');
    }

    /**
     * 显示个人停开餐设置页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        //已验证用户id
        $id = Auth::user()->id;
        $userOrderStatus = Auth::user()->userOrderStatuses;
        //开餐状态下判断？
        if ($userOrderStatus->breakfast){

        }
        //个人开餐记录
        $bookBreakfast = BookBreakfast::whereNull('end_date')->where('user_id',$id)->first();
        //个人停餐记录
        $cancelBreakfast = CancelBreakfast::whereNull('end_date')->where('user_id',$id)->first();
        return view('user.breakfast.create',[
            'bookBreakfast' => $bookBreakfast,
            'cancelBreakfast' => $cancelBreakfast,
        ]);
    }

    public function store(Request $request)
    {
        dd($request->input());
    }
}
