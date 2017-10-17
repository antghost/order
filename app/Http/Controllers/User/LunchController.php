<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\BookLunch;
use App\Models\CancelLunch;

class LunchController extends Controller
{
    public function index()
    {
        return view('user.lunch.index');
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
        if ($userOrderStatus->lunch){
            //开餐状态，开餐结束日期为空；停餐结束日期为有效记录
            $bookLunch = BookLunch::whereNull('end_date')->where('user_id',$id)->first();
            $cancelLunch = CancelLunch::where([['user_id', '=', $id],['end_date', '>=', date('Y-m-d')]])->first();
            //当开始日期为昨天或之前时限制为只读
            if (isset($cancelLunch)) {
                $beginDate = Carbon::parse($cancelLunch->begin_date);
                $beginDate->lt(Carbon::today()) ? $readonly = true : $readonly = false;
            } else {
                $readonly = false;
            }
        } else {
            //停餐状态，开餐结束日期为空；开餐结束日期为有效记录
            $bookLunch = BookLunch::where([['user_id', $id],['end_date', '>=', date('Y-m-d')]])->first();
            $cancelLunch = CancelLunch::whereNull('end_date')->where('user_id',$id)->first();
            //当开始日期为昨天或之前时限制为只读
            if (isset($bookLunch)) {
                $beginDate = Carbon::parse($bookLunch->begin_date);
                $beginDate->lt(Carbon::today()) ? $readonly = true : $readonly = false;
            } else {
                $readonly = false;
            }
        }

        return view('user.Lunch.create',[
            'bookLunch' => $bookLunch,
            'cancelLunch' => $cancelLunch,
            'readonly' => $readonly,
        ]);
    }

    /**
     * 保存个人停开餐设置
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        //数据验证
        $this->validate($request, [
            'type' => 'required|string',
            'begin_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $userID = Auth::user()->id;
        $id = $request->input('id');
        $type = $request->input('type');
        $beginDate = Carbon::parse($request->input('begin_date'));
        $endDate = Carbon::parse($request->input('end_date'));

        $orderTime = DB::table('order_times')->where('type', 2)->first(); //午餐停开餐时限
        $bookTime = $orderTime->book_time;
        $cancelTime = $orderTime->cancel_time;

        //开始日期不能大于结束日期判断
        if ($beginDate->gt($endDate)){
            return redirect()->back()->withErrors('开始日期不能大于结束日期')->withInput();
        }

        //新增或修改开餐记录
        if ($type == 'book'){
            //开餐时限判断，超过时限不能开餐
            if ($beginDate == Carbon::today() && date("H:i:s") > $bookTime){
                return redirect()->back()->withErrors('当天请在 '.$bookTime.' 前开餐')->withInput();
            }
            $bookLunch = BookLunch::updateOrCreate(
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
            if (isset($bookLunch)){
                return redirect()->back()->with('status', '提交成功');
            }
        }

        //新增或修改停餐记录
        if ($type == 'cancel'){
            $cancelLunch = CancelLunch::updateOrCreate(
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
            if (isset($cancelLunch)){
                return redirect()->back()->with('status', '提交成功');
            }
        }

        return redirect()->back();
    }
}
