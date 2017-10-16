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
        //停开餐状态判断
        if ($userOrderStatus->breakfast){
            //开餐状态，开餐结束日期为空；停餐结束日期为有效记录
            $bookBreakfast = BookBreakfast::whereNull('end_date')->where('user_id',$id)->first();
            $cancelBreakfast = CancelBreakfast::where([['user_id', '=', $id],['end_date', '>=', date('Y-m-d')]])->first();
        } else {
            //停餐状态，开餐结束日期为空；开餐结束日期为有效记录
            $bookBreakfast = BookBreakfast::where([['user_id', $id],['end_date', '>=', date('Y-m-d')]])->first();
            $cancelBreakfast = CancelBreakfast::whereNull('end_date')->where('user_id',$id)->first();
        }

        return view('user.breakfast.create',[
            'bookBreakfast' => $bookBreakfast,
            'cancelBreakfast' => $cancelBreakfast,
        ]);
    }

    public function store(Request $request)
    {
//        dd($request->input());
        $this->validate($request, [
            'type' => 'required|string',
            'begin_date' => 'required',
            'end_date' => 'required',
        ]);

        $userID = Auth::user()->id;
        $id = $request->input('id');
        $type = $request->input('type');
        $beginDate = $request->input('begin_date');
        $endDate = $request->input('end_date');

        if ($type == 'book'){
            BookBreakfast::updateOrCreate(
                [
                    'id' => $id,
                    'user_id' => $userID,
                ],
                [
                    'begin_date' => $beginDate,
                    'end_date' => $endDate,
                    'user_id' => $userID,
                ]
            );
        }

        if ($type == 'cancel'){
            CancelBreakfast::updateOrCreate(
                [
                    'id' => $id,
                    'user_id' => $userID,
                ],
                [
                    'begin_date' => $beginDate,
                    'end_date' => $endDate,
                    'user_id' => $userID,
                ]
            );
        }

        return redirect()->back();

    }
}
