<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\BookDinner;
use App\Models\CancelDinner;

class DinnerController extends Controller
{
    public function index()
    {
        $dinner = Auth::user()->userOrderStatuses->dinner;
        $dinner ? $msg = '当前处于长期开餐状态。' : $msg='当前处于长期停餐状态';
        return view('user.dinner.index', ['msg' => $msg]);
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
        $orderTime = DB::table('order_times')->where('type', 3)->first(); //晚餐停开餐时限
        //用于日历控件中的最小日期值，超过时限只能选择明天
        date('H:i:s') > $orderTime->book_time ? $bookMinDate = date('Y-m-d', strtotime('+1 day'))
            : $bookMinDate = date('Y-m-d');
        date('H:i:s') > $orderTime->cancel_time ? $cancelMinDate = date('Y-m-d', strtotime('+1 day'))
            : $cancelMinDate = date('Y-m-d');

        //停开餐状态判断
        if ($userOrderStatus->dinner){
            //开餐状态，开餐结束日期为空；停餐结束日期为有效记录
            $bookDinner = BookDinner::whereNull('end_date')->where('user_id',$id)->first();
            $cancelDinner = CancelDinner::where([['user_id', '=', $id],['end_date', '>=', date('Y-m-d')]])->first();
            //当开始日期为昨天或之前时限制为只读
            if (isset($cancelDinner)) {
                $beginDate = Carbon::parse($cancelDinner->begin_date);
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
            $bookDinner = BookDinner::where([['user_id', $id],['end_date', '>=', date('Y-m-d')]])->first();
            $cancelDinner = CancelDinner::whereNull('end_date')->where('user_id',$id)->first();
            //当开始日期为昨天或之前时限制为只读
            if (isset($bookDinner)) {
                $beginDate = Carbon::parse($bookDinner->begin_date);
                ($beginDate->lt(Carbon::today())
                    || ($beginDate->eq(Carbon::today()) && date('H:i:s')>$orderTime->book_time) )
                    ? $readonly = true : $readonly = false;
                $status = 'update';
            } else {
                $readonly = false;
                $status = 'create';
            }
        }

        return view('user.Dinner.create',[
            'bookDinner' => $bookDinner,
            'cancelDinner' => $cancelDinner,
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

        $orderTime = DB::table('order_times')->where('type', 3)->first(); //晚餐停开餐时限
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
            $bookDinner = BookDinner::updateOrCreate(
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
            if (isset($bookDinner)){
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
            $cancelDinner = CancelDinner::updateOrCreate(
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
            if (isset($cancelDinner)){
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
        $dinner = Auth::user()->userOrderStatuses->dinner;
        $dinner ? $msg = '当前处于长期开餐状态。' : $msg='当前处于长期停餐状态';

        $request->flashOnly(['begin_date','end_date']);

        $userId = Auth::user()->id;
        $beginDate = $request->input('begin_date');
        $endDate = $request->input('end_date');
        $book = $request->input('book');
        $cancel = $request->input('cancel');

        $dinners = DB::table('v_dinners')
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
//            $dinners = $dinners;
        } else {
            $dinners = $dinners
                //开餐勾选
                ->when($book, function ($query) {
                    $query->where('type', '开餐');
                })
                //停餐勾选
                ->when($cancel, function ($query) {
                    $query->where('type', '停餐');
                });
        }

        $dinners = $dinners->where('user_id', $userId)->orderBy('begin_date')->paginate(15);

        //分页参数
        $dinners = $dinners->appends([
            'begin_date' => $beginDate,
            'end_date' => $endDate,
        ]);
        //分页勾选参数
        if (isset($book)) $dinners = $dinners->appends(['book' => 'on']);
        if (isset($cancel)) $dinners = $dinners->appends(['cancel' => 'on']);

        return view('user.dinner.index', ['dinners' => $dinners, 'msg' => $msg]);
    }
    
}
