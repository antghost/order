<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\BookBreakfast;
use App\Models\CancelBreakfast;

class BreakfastController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
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
            $cancelBreakfast = CancelBreakfast::where([['user_id', '=', $id],['end_date', '>', date('Y-m-d')]])->first();
            //当开始日期为昨天或之前时限制为只读
            if (isset($cancelBreakfast)) {
                $beginDate = Carbon::parse($cancelBreakfast->begin_date);
                $beginDate->lte(Carbon::today()) ? $readonly = true : $readonly = false;
            } else {
                $readonly = false;
            }
        } else {
            //停餐状态，开餐结束日期为空；开餐结束日期为有效记录
            $bookBreakfast = BookBreakfast::where([['user_id', $id],['end_date', '>', date('Y-m-d')]])->first();
            $cancelBreakfast = CancelBreakfast::whereNull('end_date')->where('user_id',$id)->first();
            //当开始日期为昨天或之前时限制为只读
            if (isset($bookBreakfast)) {
                $beginDate = Carbon::parse($bookBreakfast->begin_date);
                $beginDate->lte(Carbon::today()) ? $readonly = true : $readonly = false;
            } else {
                $readonly = false;
            }
        }

        return view('user.breakfast.create',[
            'bookBreakfast' => $bookBreakfast,
            'cancelBreakfast' => $cancelBreakfast,
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

        //开始日期不能大于结束日期判断
        if ($beginDate->gt($endDate)){
            return redirect()->back()->withErrors('开始日期不能大于结束日期')->withInput();
        }

        //新增或修改开餐记录
        if ($type == 'book'){
            $bookBreakfast = BookBreakfast::updateOrCreate(
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
            if (isset($bookBreakfast)){
                return redirect()->back()->with('status', '提交成功');
            }
        }

        //新增或修改停餐记录
        if ($type == 'cancel'){
            $cancelBreakfast = CancelBreakfast::updateOrCreate(
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
            if (isset($cancelBreakfast)){
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
        $request->flashOnly(['begin_date','end_date']);

        $userId = Auth::user()->id;
        $beginDate = $request->input('begin_date');
        $endDate = $request->input('end_date');
        $book = $request->input('book');
        $cancel = $request->input('cancel');

        $breakfasts = DB::table('v_breakfasts')
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
//            $breakfasts = $breakfasts;
        } else {
            $breakfasts = $breakfasts
                //开餐勾选
                ->when($book, function ($query) {
                    $query->where('type', '开餐');
                })
                //停餐勾选
                ->when($cancel, function ($query) {
                    $query->where('type', '停餐');
                });
        }

        $breakfasts = $breakfasts->where('user_id', $userId)->paginate(15);

        //分页参数
        $breakfasts = $breakfasts->appends([
            'begin_date' => $beginDate,
            'end_date' => $endDate,
        ]);
        //分页勾选参数
        if (isset($book)) $breakfasts = $breakfasts->appends(['book' => 'on']);
        if (isset($cancel)) $breakfasts = $breakfasts->appends(['cancel' => 'on']);

        return view('user.breakfast.index', ['breakfasts' => $breakfasts]);
    }
}
