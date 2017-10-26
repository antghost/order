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
        $lunch = Auth::user()->userOrderStatuses->lunch;
        $lunch ? $msg = '当前处于长期开餐状态。' : $msg='当前处于长期停餐状态';
        return view('user.lunch.index', ['msg' => $msg]);
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
        $orderTime = DB::table('order_times')->where('type', 2)->first(); //午餐停开餐时限
        //用于日历控件中的最小日期值，超过时限只能选择明天
        date('H:i:s') > $orderTime->book_time ? $bookMinDate = date('Y-m-d', strtotime('+1 day'))
            : $bookMinDate = date('Y-m-d');
        date('H:i:s') > $orderTime->cancel_time ? $cancelMinDate = date('Y-m-d', strtotime('+1 day'))
            : $cancelMinDate = date('Y-m-d');
        //停开餐状态判断
        if ($userOrderStatus->lunch){
            //开餐状态，开餐结束日期为空；停餐结束日期为有效记录
            $bookLunch = BookLunch::whereNull('end_date')->where('user_id',$id)->first();
            $cancelLunch = CancelLunch::where([['user_id', '=', $id],['end_date', '>=', date('Y-m-d')]])->first();
            //当开始日期为昨天或之前时限制为只读
            if (isset($cancelLunch)) {
                $beginDate = Carbon::parse($cancelLunch->begin_date);
                ($beginDate->lt(Carbon::today())
                    || ($beginDate->eq(Carbon::today()) && date('H:i:s')>$orderTime->cancel_time) )
                    ? $readonly = true : $readonly = false;
                $status = 'update';
            } else {
                $readonly = false;
                $status = 'create';
            }
        } else {
            //停餐状态，开餐结束日期为空；开餐结束日期为有效记录
            $bookLunch = BookLunch::where([['user_id', $id],['end_date', '>=', date('Y-m-d')]])->first();
            $cancelLunch = CancelLunch::whereNull('end_date')->where('user_id',$id)->first();
            //当开始日期为昨天或之前时限制为只读
            if (isset($bookLunch)) {
                $beginDate = Carbon::parse($bookLunch->begin_date);
                ($beginDate->lt(Carbon::today())
                    || ($beginDate->eq(Carbon::today()) && date('H:i:s')>$orderTime->book_time) )
                    ? $readonly = true : $readonly = false;
                $status = 'update';
            } else {
                $readonly = false;
                $status = 'create';
            }
        }

        return view('user.Lunch.create',[
            'bookLunch' => $bookLunch,
            'cancelLunch' => $cancelLunch,
            'orderTime' => $orderTime,
            'readonly' => $readonly,
            'status' => $status,
            'bookMinDate' => $bookMinDate,
            'cancelMinDate' => $cancelMinDate,
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
        $status = $request->input('status');
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
            //时限判断，超过时限不能开餐
            if ( ($status == 'create' && $beginDate->eq(Carbon::today()) && date("H:i:s") > $bookTime)
                || ($status == 'update' && $endDate->eq(Carbon::today()) && date("H:i:s") > $bookTime) ){
                return redirect()->back()->withErrors('当天须在 '.$bookTime.' 前开餐')->withInput();
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
            //时限判断，超过时限不能开餐
            if ( ($status == 'create' && $beginDate->eq(Carbon::today()) && date("H:i:s") > $cancelTime)
                || ($status == 'update' && $endDate->eq(Carbon::today()) && date("H:i:s") > $cancelTime) ){
                return redirect()->back()->withErrors('当天须在 '.$bookTime.' 前停餐')->withInput();
            }
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

    /**
     * 搜索
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function s(Request $request)
    {
        $lunch = Auth::user()->userOrderStatuses->lunch;
        $lunch ? $msg = '当前处于长期开餐状态。' : $msg='当前处于长期停餐状态';

        $request->flashOnly(['begin_date','end_date']);

        $userId = Auth::user()->id;
        $beginDate = $request->input('begin_date');
        $endDate = $request->input('end_date');
        $book = $request->input('book');
        $cancel = $request->input('cancel');

        $lunches = DB::table('v_lunches')
            //开始日期
            ->when($beginDate, function ($query) use ($beginDate){
                $query->where('begin_date','>=', $beginDate);
            })
            //结束日期
            ->when($endDate, function ($query) use ($endDate){
                $query->where('end_date','<=', $endDate);
            });

        //勾选判断
        if (isset($book) && isset($cancel)){
//            $lunches = $lunches;
        } else {
            $lunches = $lunches
                //开餐勾选
                ->when($book, function ($query) {
                    $query->where('type', '开餐');
                })
                //停餐勾选
                ->when($cancel, function ($query) {
                    $query->where('type', '停餐');
                });
        }

        $lunches = $lunches->where('user_id', $userId)->orderBy('begin_date')->paginate(15);

        //分页参数
        $lunches = $lunches->appends([
            'begin_date' => $beginDate,
            'end_date' => $endDate,
        ]);
        //分页勾选参数
        if (isset($book)) $lunches = $lunches->appends(['book' => 'on']);
        if (isset($cancel)) $lunches = $lunches->appends(['cancel' => 'on']);

        return view('user.lunch.index', ['lunches' => $lunches, 'msg' => $msg]);
    }
    
}
