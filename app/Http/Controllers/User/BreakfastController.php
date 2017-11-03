<?php

namespace App\Http\Controllers\User;

use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\BookBreakfast;

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
        //已验证用户
        $user = Auth::user();
        $orderTime = DB::table('order_times')->where('type', 1)->first(); //停开餐时限

        $today = Carbon::today();
        //用于日历控件中的最小日期值，超过时限只能选择明天
        date('H:i:s') > $orderTime->book_time ? $bookMinDate = $today->copy()->addDay()->toDateString()
            : $bookMinDate = $today->toDateString();
        date('H:i:s') > $orderTime->cancel_time ? $cancelMinDate = $today->copy()->addDay()->toDateString()
            : $cancelMinDate = $today->toDateString();

        //前段有效记录
        $bookFirst = $user->bookBreakfasts()->whereNotNull('end_date')->where('end_date', '>=', $today->toDateString())->first();
        //后段有效记录
        $bookSecond = $user->bookBreakfasts()->whereNull('end_date')->first();

        //当开始日期为昨天或之前时限制为只读
        if (isset($bookFirst)){
            //停餐开始日期
            $cancelBeginDate = Carbon::parse($bookFirst->end_date)->copy()->addDay();
            $bookFirstBeginDate = Carbon::parse($bookFirst->begin_date);
            ($bookFirstBeginDate->lt(Carbon::today())
                || ($bookFirstBeginDate->eq(Carbon::today()) && date('H:i:s')>$orderTime->book_time) )
                ? $bookFirstReadonly = true : $bookFirstReadonly = false;
        } else {
            $cancelBeginDate = null;
            $bookFirstReadonly = false;
        }

        if (isset($bookSecond)) {
            //停餐结束日期
            $cancelEndDate = Carbon::parse($bookSecond->begin_date)->subDay();
            $bookSecondBeginDate = Carbon::parse($bookSecond->begin_date);
            ($bookSecondBeginDate->lt(Carbon::today())
                || ($bookSecondBeginDate->eq(Carbon::today()) && date('H:i:s')>$orderTime->book_time) )
                ? $bookSecondReadonly = true : $bookSecondReadonly = false;
            //如果前段有效记录存在，开餐最小日期为前期记录结束日期+1
            if (isset($bookFirst)) $bookMinDate = $cancelBeginDate->toDateString();
        } else {
            $cancelEndDate = null;
            $bookSecondReadonly = false;
        }
        //当停餐开始日期$cancelBeginDate为null时
        if (is_null($cancelBeginDate)) $cancelEndDate = null;
        if (!is_null($cancelBeginDate) && !is_null($cancelEndDate) && $cancelBeginDate->gt($cancelEndDate)) {
            $cancelBeginDate = null;
            $cancelEndDate = null;
        }
        if (!is_null($cancelBeginDate)) $cancelBeginDate = $cancelBeginDate->toDateString();
        if (!is_null($cancelEndDate)) $cancelEndDate = $cancelEndDate->toDateString();

        (Carbon::parse($cancelBeginDate)->lt(Carbon::today())
            || (Carbon::parse($cancelBeginDate)->eq(Carbon::today()) && date('H:i:s')>$orderTime->cancel_time) )
            ? $cancelReadonly = true : $cancelReadonly = false;

        return view('user.breakfast.create',[
            'bookFirst' => $bookFirst,
            'bookSecond' => $bookSecond,
            'orderTime' => $orderTime,
            'bookMinDate' => $bookMinDate,
            'cancelMinDate' => $cancelMinDate,
            'bookFirstReadonly' => $bookFirstReadonly,
            'bookSecondReadonly' => $bookSecondReadonly,
            'cancelBeginDate' => $cancelBeginDate,
            'cancelEndDate' => $cancelEndDate,
            'cancelReadonly' => $cancelReadonly,
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
            'end_date' => 'nullable|date',
        ]);

        //已验证用户
        $user = Auth::user();

        $id = $request->input('id');
        $type = $request->input('type');
        $bookFirstId = $request->input('bookFirstId');
        //开餐结束日期不为空的结束日期
        $bookFirstEndDate = Carbon::parse($request->input('bookFirstEndDate'));
        $bookSecondId = $request->input('bookSecondId');
        //停餐开始日期
        $beginDate = Carbon::parse($request->input('begin_date'));
        //停餐结束日期
        $endDate = $request->input('end_date');

        //开始日期不能大于结束日期判断
        if (!is_null($endDate)) {
            //当$endDate=null时Carbon::parse()取当前时间，所以不能在$endDate=null时Carbon
            $endDate = Carbon::parse($endDate);
            if ($beginDate->gt($endDate)) {
                return redirect()->back()->withErrors('开始日期不能大于结束日期')->withInput();
            }
        }

        //新增或修改开餐记录
        if ($type == 'book'){
            $bookBreakfast = BookBreakfast::updateOrCreate(
                [
                    'id' => $id,
                    'user_id' => $user->id,
                ],
                [
                    'begin_date' => $beginDate,
                    'end_date' => $endDate,
                    'user_id' => $user->id,
                ]
            );
            if (isset($bookBreakfast)){
                return redirect()->back()->with('status', '提交成功');
            }
        }

        //新增或修改停餐记录
        if ($type == 'cancel'){
            DB::beginTransaction();

            //开餐结束日期不为空的有效记录处理
            if (!is_null($bookFirstId)){
                //开餐结束日期为空的记录不存在时处理
                if (is_null($bookSecondId)){
                    //当停餐开始日期大于有效开餐日期时直接返回
                    if ($beginDate->gt($bookFirstEndDate)) {
                        return redirect()->back()->with('status', '该时间段没有开餐无须停餐');
                    } else {
                        //当停餐结束日期小于开餐结束日期时插入开餐后段有效记录
                        if (!is_null($endDate)) {
                            if ($endDate->lt($bookFirstEndDate)) {
                                $bookSecond = BookBreakfast::create([
                                    'begin_date' => $endDate->copy()->addDay(),
                                    'end_date' => $bookFirstEndDate,
                                    'user_id' => $user->id,
                                ]);
                            }
                        }
                    }
                }
                //前段有效开餐记录的结束日期=停餐开始日期-1
                $bookFirst = BookBreakfast::where('id', $bookFirstId)->update(['end_date' => $beginDate->copy()->subDay()]);
            }

            //开餐结束日期为空（长期）的有效记录处理
            if (!is_null($bookSecondId)){
                //开餐结束日期不为空且有效的记录不存在时处理
                if (is_null($bookFirstId)) {
                    if (!is_null($endDate)) {
                        //当停餐结束日期不为空时
                        $bookFirst = BookBreakfast::where('id', $bookSecondId)->update(['end_date' => $beginDate->copy()->subDay()]);
                        //增加后段有效开餐记录
                        $bookSecond = BookBreakfast::create([
                            'begin_date' => $endDate->copy()->addDay(),
                            'end_date' => null,
                            'user_id' => $user->id,
                        ]);
                    } else {
                        //停餐结束日期为空时开餐记录结束日期=停餐开始日期-1
                        $bookSecond = BookBreakfast::where('id', $bookSecondId)->update(['end_date' => $beginDate->copy()->subDay()]);
                    }
                } else {
                    if (!is_null($endDate)) {
                        //停餐结束日期不为空时开餐记录开始日期=停餐结束日期+1
                        $bookSecond = BookBreakfast::where('id', $bookSecondId)->update(['begin_date' => $endDate->copy()->addDay()]);
                    } else {
                        //当停餐结束日期为空（长期）时，删除长期开餐记录
                        BookBreakfast::where('id', $bookSecondId)->delete();
                        //修改前段开餐记录结束日期=停餐开始日期-1
                        BookBreakfast::where('id', $bookFirstId)->update(['end_date' => $beginDate->copy()->subDay()]);
                    }
                }
            }

            if (isset($bookFirst) || isset($bookSecond)){
                DB::commit();
                return redirect()->back()->with('status', '提交成功');
            } else {
                DB::rollBack();
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

        $user = Auth::user();
        $beginDate = $request->input('begin_date');
        $endDate = $request->input('end_date');

        $breakfasts = $user->bookBreakfasts()
            //开始日期
            ->when($beginDate, function ($query) use ($beginDate){
                $query->where('begin_date','>=', $beginDate);
            })
            //结束日期
            ->when($endDate, function ($query) use ($endDate){
                $query->where('end_date','<=', $endDate);
            });

        dd($breakfasts->pluck('user_id','begin_date')->toJson());

        $breakfasts = $breakfasts->orderBy('begin_date')->paginate(15);

        //分页参数
        $breakfasts = $breakfasts->appends([
            'begin_date' => $beginDate,
            'end_date' => $endDate,
        ]);

        return view('user.breakfast.index', ['breakfasts' => $breakfasts]);
    }
}
