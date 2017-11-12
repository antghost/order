<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\BookBreakfast;
use App\Models\BookDinner;
use App\Models\BookLunch;
use App\Models\Calendar;

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

        $bookRecords = $user->bookBreakfasts()
            ->where(function ($query) use ($today) {
                $query->whereNull('end_date')->orWhere('end_date', '>=', $today->toDateString());
            })->orderBy('begin_date');

        $bookFirst = null;
        $bookSecond = null;

        if ($bookRecords->count() == 0) {
            $bookFirst = null;
            $bookSecond = null;
        }

        if ($bookRecords->count() == 1) {
            // 前段有效记录
            $bookFirst = $bookRecords->first();
            $bookSecond = null;
        }

        if ($bookRecords->count() == 2) {
            // 前段有效记录
            $bookFirst = $bookRecords->limit(1)->first();
            // 后段有效记录 略过第一条数据取第二条
            $bookSecond = $bookRecords->offset(1)->limit(1)->first();
        }

        //当开始日期为昨天或之前时限制为只读
        if (isset($bookFirst)){
            //停餐开始日期=为前段记录结束日期+1
            is_null($bookFirst->end_date) ? $cancelBeginDate = null
                :$cancelBeginDate = Carbon::parse($bookFirst->end_date)->copy()->addDay();
//            if (!is_null($cancelBeginDate)) $cancelMinDate = $cancelBeginDate;
            $bookFirstBeginDate = Carbon::parse($bookFirst->begin_date);
            //前段记录开始日期小于当天或在当天但大于开餐时限时，设置日期选择为只读
            ($bookFirstBeginDate->lt(Carbon::today())
                || ($bookFirstBeginDate->eq(Carbon::today()) && date('H:i:s')>$orderTime->book_time) )
                ? $bookFirstReadonly = true : $bookFirstReadonly = false;
            //用于日历控件中的停餐最小日期值
//            if(!isset($bookSecond)) $cancelMinDate = $bookFirstBeginDate->copy()->toDateString();
        } else {
            $cancelBeginDate = null;
            $bookFirstReadonly = false;
        }

        if (isset($bookSecond)) {
            //停餐结束日期
            $cancelEndDate = Carbon::parse($bookSecond->begin_date)->subDay();
            $bookSecondBeginDate = Carbon::parse($bookSecond->begin_date);
            //后段记录开始日期小于当天或在当天但大于开餐时限时，设置日期选择为只读
            ($bookSecondBeginDate->lt(Carbon::today())
                || ($bookSecondBeginDate->eq(Carbon::today()) && date('H:i:s')>$orderTime->book_time) )
                ? $bookSecondReadonly = true : $bookSecondReadonly = false;
            //如果前段有效记录存在，开餐最小日期为前段记录结束日期+1（即停餐开始日期）
            if (isset($bookFirst)) $bookMinDate = $cancelBeginDate->toDateString();
        } else {
            $cancelEndDate = null;
            $bookSecondReadonly = false;
        }
        //当停餐开始日期$cancelBeginDate为null时，停餐结束日期也为null
        if (is_null($cancelBeginDate)) $cancelEndDate = null;
        //当停餐开始与结束日期都不为空但开始日期大于结束日期时，设置两者都为空
        if (!is_null($cancelBeginDate) && !is_null($cancelEndDate) && $cancelBeginDate->gt($cancelEndDate)) {
            $cancelBeginDate = null;
            $cancelEndDate = null;
        }
        if (!is_null($cancelBeginDate)) $cancelBeginDate = $cancelBeginDate->toDateString();
        if (!is_null($cancelEndDate)) $cancelEndDate = $cancelEndDate->toDateString();

        //停餐开始日期小于当天或在当天但大于停餐时限时，设置日期选择为只读
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
//        $this->validate($request, [
//            'type' => 'required|string',
//            'begin_date' => 'required|date',
//            'end_date' => 'nullable|date',
//        ]);

        //已验证用户
        $user = Auth::user();

        $type = $request->input('type');

        //前段记录
        $firstId = $request->input('first_id');
        $firstBeginDate = $request->input('first_begin_date');
        $firstEndDate = $request->input('first_end_date');
        //后段记录
        $secondId = $request->input('second_id');
        $secondBeginDate = $request->input('second_begin_date');
        $secondEndDate = $request->input('second_end_date');
        //新开餐日期
        $bookBeginDate = $request->input('book_begin_date');
        $bookEndDate = $request->input('book_end_date');
        //停餐日期
        $beginDate = $request->input('begin_date');
        $endDate = $request->input('end_date');

        //开始日期不能大于结束日期判断
        //当$endDate=null时Carbon::parse()取当前时间，所以不能在$endDate=null时Carbon
        if (!is_null($firstBeginDate)) $firstBeginDate = Carbon::parse($firstBeginDate);
        if (!is_null($firstEndDate)) {
            $firstEndDate = Carbon::parse($firstEndDate);
            if ($firstBeginDate->gt($firstEndDate)) {
                return redirect()->back()->withErrors('开始日期不能大于结束日期')->withInput();
            }
        }
        //当$endDate=null时Carbon::parse()取当前时间，所以不能在$endDate=null时Carbon
        if (!is_null($secondBeginDate)) $secondBeginDate = Carbon::parse($secondBeginDate);
        if (!is_null($secondEndDate)) {
            $secondEndDate = Carbon::parse($secondEndDate);
            if ($secondBeginDate->gt($secondEndDate)) {
                return redirect()->back()->withErrors('开始日期不能大于结束日期')->withInput();
            }
        }
        //当$endDate=null时Carbon::parse()取当前时间，所以不能在$endDate=null时Carbon
        if (!is_null($bookBeginDate)) $bookBeginDate = Carbon::parse($bookBeginDate);
        if (!is_null($bookEndDate)) {
            $bookEndDate = Carbon::parse($bookEndDate);
            if ($bookBeginDate->gt($bookEndDate)) {
                return redirect()->back()->withErrors('开始日期不能大于结束日期')->withInput();
            }
        }
        //当$endDate=null时Carbon::parse()取当前时间，所以不能在$endDate=null时Carbon
        if (!is_null($beginDate)) $beginDate = Carbon::parse($beginDate);
        if (!is_null($endDate)) {
            $endDate = Carbon::parse($endDate);
            if ($beginDate->gt($endDate)) {
                return redirect()->back()->withErrors('开始日期不能大于结束日期')->withInput();
            }
        }

        //新增或修改开餐记录
        if ($type == 'book'){
            //无有效开餐记录时新增
            if (!is_null($bookBeginDate)) {
                $bookNew = BookBreakfast::create(
                    [
                        'begin_date' => $bookBeginDate,
                        'end_date' => $bookEndDate,
                        'user_id' => $user->id,
                    ]
                );
                if (isset($bookNew)) {
                    return redirect()->back()->with('status', '提交成功');
                }
            }

            if (!is_null($firstId)) {
                if (is_null($secondId)) {
                    $bookFirst = BookBreakfast::where('id', $firstId)->update([
                        'begin_date' => $firstBeginDate,
                        'end_date' => $firstEndDate,
                    ]);
                    if (isset($bookFirst)) {
                        return redirect()->back()->with('status', '提交成功');
                    }
                } else {
                    $bookSecond = BookBreakfast::where('id', $secondId)->update([
                        'begin_date' => $secondBeginDate,
                        'end_date' => $secondEndDate,
                    ]);
                    if (isset($bookSecond)) {
                        return redirect()->back()->with('status', '提交成功');
                    }
                }
            }

        }

        //新增或修改停餐记录
        if ($type == 'cancel'){
            DB::beginTransaction();

//            if (!is_null($firstId) && is_null($secondId)) {
//                if ((!is_null($firstEndDate) && $beginDate->gt($firstEndDate)) || (!is_null($endDate) && $endDate->lt($firstBeginDate)) ) {
//                    return redirect()->back()->with('status', '该时间段没有开餐无须停餐，请重新设置');
//                }
//                // 停餐开始日期<=开餐开始日期，停餐结束日期为空或停餐结束日期>=开餐结束日期
//                if ($beginDate->lte($firstBeginDate) && (is_null($endDate) || (!is_null($endDate) && !is_null($firstEndDate) && $endDate->gte($firstEndDate)) ) ) {
//                    $bookFirst = BookBreakfast::where('id', $firstId)->delete();
//                }
//                // 停餐开始日期<=开餐开始日期，停餐结束日期不为空
//                if ($beginDate->lte($firstBeginDate) && !is_null($endDate)) {
//                    //开餐结束日期为空或（开餐结束日期不为空且大于停餐结束日期），修改开餐开始日期=停餐结束日期+1
//                    if (is_null($firstEndDate) || (!is_null($firstEndDate) && $endDate-lt($firstEndDate))) {
//                        $bookFirst = BookBreakfast::where('id', $firstId)->update(['begin_date' => $endDate->copy()->addDay()]);
//                    }
//                }
//                // 停餐开始日期>开餐开始日期
//                if ($beginDate->gt($firstBeginDate)){
//                    //停餐结束日期为空或停餐结束日期>=开餐结束日期
//                    if (is_null($endDate) || (!is_null($endDate) && !is_null($firstEndDate) && $endDate->gte($firstEndDate))){
//                        $bookFirst = BookBreakfast::where('id', $firstId)->update(['end_date' => $beginDate->copy()->subDay()]);
//                    }
//                    //停餐结束日期不为空或停餐结束日期<开餐结束日期
//                    if (!is_null($endDate) && (is_null($firstEndDate) || (!is_null($firstEndDate) && $endDate->lt($firstEndDate))) ){
//                        $bookFirst = BookBreakfast::where('id', $firstId)->update(['end_date' => $beginDate->copy()->subDay()]);
//                        $bookSecond = BookBreakfast::create(
//                            [
//                                'begin_date' => $endDate->copy()->addDay(),
//                                'end_date' => $firstEndDate,
//                                'user_id' => $user->id,
//                            ]
//                        );
//                    }
//                }
//            }
//
//            if (!is_null($firstId) && !is_null($secondId)) {
//                if ((!is_null($secondEndDate) && $beginDate->gt($secondEndDate)) || (!is_null($endDate) && $endDate->lt($firstBeginDate)) ) {
//                    return redirect()->back()->with('status', '该时间段没有开餐无须停餐，请重新设置');
//                }
//                // 停餐开始日期<=前段开餐开始日期，停餐结束日期为空或停餐结束日期>=后段开餐结束日期
//                if ($beginDate->lte($firstBeginDate) && (is_null($endDate) || (!is_null($endDate) && !is_null($secondEndDate) && $endDate->gte($secondEndDate)) ) ) {
//                    $bookFirst = BookBreakfast::where('id', $firstId)->delete();
//                    $bookSecond = BookBreakfast::where('id', $secondId)->delete();
//                }
//                //停餐开始日期<=前段开餐开始日期并且停餐结束日期不为空
//                if ($beginDate->lte($firstBeginDate) && !is_null($endDate) ) {
//                    if ($endDate->lt($firstEndDate)) {
//                        $bookFirst = BookBreakfast::where('id', $firstId)->update(['begin_date' => $endDate->copy()->addDay()]);
//                    }
//                    if ($endDate->gte($firstEndDate) && $endDate->lt($secondBeginDate)) {
//                        $bookFirst = BookBreakfast::where('id', $firstId)->delete();
//                    }
//                    if ($endDate->gte($secondBeginDate) && (is_null($secondEndDate) || (!is_null($secondEndDate) && $endDate->lt($secondEndDate))) ) {
//                        $bookFirst = BookBreakfast::where('id', $firstId)->delete();
//                        $bookSecond = BookBreakfast::where('id', $secondId)->update(['begin_date' => $endDate->copy()->addDay()]);
//                    }
//                }
//                //停餐开始日期>前段开餐开始日期
//                if ($beginDate->gt($firstBeginDate)) {
//                    if (is_null($endDate) && $beginDate->lte($firstEndDate)) {
//                        $bookFirst = BookBreakfast::where('id', $firstId)->update(['end_date' => $beginDate->copy()->subDay()]);
//                        $bookSecond = BookBreakfast::where('id', $secondId)->delete();
//                    }
//                    if (is_null($endDate) && $beginDate->gt($firstEndDate)) {
//                        $bookSecond = BookBreakfast::where('id', $secondId)->delete();
//                    }
//                    if (is_null($endDate) && $beginDate->gt($secondBeginDate)) {
//                        $bookSecond = BookBreakfast::where('id', $secondId)->update(['end_date' => $beginDate->copy()->subDay()]);
//                    }
//                    //停餐结束日期不为空并且停餐开始日期<=前段开餐结束日期并且停餐结束日期<=前段开餐结束日期
//                    if (!is_null($endDate) && $beginDate->lte($firstEndDate) && $endDate->lte($firstEndDate)) {
//                        //目前暂这样处理不然会出现分段数据造成三个分段
//                        $bookFirst = BookBreakfast::where('id', $firstId)->update(['end_date' => $beginDate->copy()->subDay()]);
//                    }
//                    if (!is_null($endDate) && $beginDate->lte($firstEndDate) && $endDate->lt($secondBeginDate)) {
//                        $bookFirst = BookBreakfast::where('id', $firstId)->update(['end_date' => $beginDate->copy()->subDay()]);
//                    }
//                    if (!is_null($endDate) && $beginDate->gte($firstEndDate) && $endDate->lt($secondBeginDate)) {
//                        $bookFirst = BookBreakfast::where('id', $firstId)->update(['end_date' => $beginDate->copy()->subDay()]);
//                    }
//                    //停餐结束日期不为空并且停餐开始日期<=前段开餐结束日期并且停餐结束日期>=后段开餐开始日期
//                    if (!is_null($endDate) && $beginDate->lte($firstEndDate) && $endDate->gte($secondBeginDate)) {
//                        if (is_null($secondEndDate) || ( !is_null($secondEndDate) && $endDate-lt($secondEndDate) )) {
//                            $bookFirst = BookBreakfast::where('id', $firstId)->update(['end_date' => $beginDate->copy()->subDay()]);
//                            $bookSecond = BookBreakfast::where('id', $secondId)->update(['begin_date' => $endDate->copy()->addDay()]);
//                        }
//                        if (!is_null($secondEndDate) && $endDate->gte($secondEndDate)) {
//                            $bookFirst = BookBreakfast::where('id', $firstId)->update(['end_date' => $beginDate->copy()->subDay()]);
//                            $bookSecond = BookBreakfast::where('id', $secondId)->delete();
//                        }
//                    }
//                    //停餐结束日期不为空并且停餐开始日期>=后段开餐开始日期
//                    if (!is_null($endDate) && $beginDate->gte($secondBeginDate)) {
//                        if (is_null($secondEndDate) && $beginDate->eq($secondBeginDate)) {
//                            $bookSecond = BookBreakfast::where('id', $secondId)->update(['begin_date' => $endDate->copy()->addDay()]);
//                        }
//                        if (is_null($secondEndDate) && $beginDate->gt($secondBeginDate)) {
//                            //暂不作处理不然会产生三段数据
//                            return redirect()->back()->with('status', '请在'.$firstEndDate.'之后再设置。');
//                        }
//                        if (!is_null($secondEndDate) && $beginDate->eq($secondBeginDate) && $endDate->gte($secondEndDate)) {
//                            $bookSecond = BookBreakfast::where('id', $secondId)->delete();
//                        }
//                        if (!is_null($secondEndDate) && $beginDate->gt($secondBeginDate) && $beginDate->lt($secondEndDate) && $endDate->gte($secondEndDate)){
//                            $bookSecond = BookBreakfast::where('id', $secondId)->update(['end_date' => $beginDate->copy()->subDay()]);
//                        }
//                        if (!is_null($secondEndDate) && $beginDate->gt($secondBeginDate) && $endDate->lt($secondEndDate)){
//                            //暂不作处理不然会产生三段数据
//                            return redirect()->back()->with('status', '请在'.$firstEndDate.'之后再设置。');
//                        }
//                    }
//                }
//            }

            //前段开餐有效记录处理
            if (!is_null($firstId)){
                //开餐结束日期为空的记录不存在时处理
                if (is_null($secondId)){
                    //当停餐开始日期大于有效开餐日期时直接返回
                    if (!is_null($firstEndDate) && $beginDate->gt($firstEndDate)) {
                        return redirect()->back()->with('status', '该时间段没有开餐无须停餐');
                    } else {
                        //当停餐结束日期小于开餐结束日期时插入开餐后段有效记录
                        if (!is_null($endDate)) {
                            if (!is_null($firstEndDate) && $endDate->lt($firstEndDate)) {
                                $bookSecond = BookBreakfast::create([
                                    'begin_date' => $endDate->copy()->addDay(),
                                    'end_date' => $firstEndDate,
                                    'user_id' => $user->id,
                                ]);
                            }
                        }
                    }
                }
                if ($beginDate->lte($firstBeginDate)) {
                    BookBreakfast::where('id', $firstId)->delete();
                } else {
                    //前段有效开餐记录的结束日期=停餐开始日期-1
                    if ((!is_null($firstEndDate) && $beginDate->lt($firstEndDate)) || is_null($firstEndDate))
                    $bookFirst = BookBreakfast::where('id', $firstId)->update(['end_date' => $beginDate->copy()->subDay()]);
                }
            }

            //后段开餐有效记录处理
            if (!is_null($secondId)){
                //开餐结束日期不为空且有效的记录不存在时处理
                if (!is_null($secondEndDate) && $beginDate->gt($secondEndDate)) {
                    return redirect()->back()->with('status', '该时间段没有开餐无须停餐');
                }
                if (!is_null($endDate)) {
                    if (!is_null($secondEndDate) && $endDate->lt($secondEndDate)) {
                        if ($beginDate->lte($secondBeginDate)) {
                            $bookSecond = BookBreakfast::where('id', $secondId)->update(['begin_date' => $endDate->copy()->addDay()]);
                        } else {
                            //当停餐结束日期不为空时
                            $bookSecond = BookBreakfast::where('id', $secondId)->update(['end_date' => $beginDate->copy()->subDay()]);
                        }
                    } else {
                        if ($beginDate->lte($secondBeginDate)) {
                            BookBreakfast::where('id', $secondId)->delete();
                        } else {
                            $bookSecond = BookBreakfast::where('id', $secondId)->update(['end_date' => $beginDate->copy()->subDay()]);
                        }
                    }
                } else {
                    if ($beginDate->lte($secondBeginDate)) {
                        BookBreakfast::where('id', $secondId)->delete();
                    } else {
                        //停餐结束日期为空时开餐记录结束日期=停餐开始日期-1
                        $bookSecond = BookBreakfast::where('id', $secondId)->update(['end_date' => $beginDate->copy()->subDay()]);
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


    public function s(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        $allDays = [];
        $bookDays = [];

        $beginDate = Carbon::create($year, $month)->startOfMonth();
        $endDate = Carbon::create($year, $month)->endOfMonth();

        //开餐详细日期
        $bookDays = $this->allOfDays($beginDate, $endDate, 'breakfast');
        //按日期转化为echarts日历图表所需要的数组形式
        for ($start = $beginDate; $start->lte($endDate); $start->addDay())
        {
            $temp = [];
            (in_array($start->toDateString(), $bookDays)) ? $temp = [$start->toDateString(), '早餐']
                : $temp = [$start->toDateString() , ''];
            array_push($allDays, $temp);
        }

        //echarts日历图表range: month,data: data
        $data = ['month' => $year.'-'.$month, 'data' => $allDays];
        return $data;
    }

    private function allOfDays($startDate, $endDate, $method = null)
    {
        if (strtolower($method) == 'breakfast') {
            $oneUser = Auth::user()->bookBreakfasts();
            $twoUser = Auth::user()->bookBreakfasts();
            $threeUser = Auth::user()->bookBreakfasts();
        }
        if (strtolower($method) == 'lunch') {
            $oneUser = Auth::user()->bookLunches();
            $twoUser = Auth::user()->bookLunches();
            $threeUser = Auth::user()->bookLunches();
        }
        if (strtolower($method) == 'dinner') {
            $oneUser = Auth::user()->bookDinners();
            $twoUser = Auth::user()->bookDinners();
            $threeUser = Auth::user()->bookDinners();
        }

        $days = [];
        //情况1：$startDate：10月1日，$endDate：10月31日，数据记录开始与结束日期都为10月
        $oneRecords = $oneUser->where([
            ['begin_date', '>=', $startDate],
            ['end_date', '<=', $endDate],
        ])->get();

        if ($oneRecords->count() > 0){
            foreach ($oneRecords as $oneRecord){
                $days = $this->detailInDays($oneRecord->begin_date, $oneRecord->end_date, $days);
            }
        }

        //情况2：$startDate：10月1日，$endDate：10月31日，数据记录开始日期为9月或之前，结束日期为10月或之后(理论上应该只有1条这样的数据)
        $twoRecord = $twoUser->where('begin_date', '<', $startDate)
            ->where(function ($query) use ($startDate){
                $query->where('end_date', '>=', $startDate)
                    ->orWhereNull('end_date');
            })
            ->first();

        if (isset($twoRecord)){
            //开始日期为$startDate，结束日期为$twoRecord->end_date或$endDate
            (is_null($twoRecord->end_date)) ? $twoEndDate = $endDate :
                ($twoRecord->end_date >= $endDate) ? $twoEndDate = $endDate : $twoEndDate = $twoRecord->end_date;
            $days = $this->detailInDays($startDate, $twoEndDate, $days);
        }

        //情况3：$startDate：10月1日，$endDate：10月31日，数据记录开始日期为10月，结束日期为11月或之后(理论上应该只有1条这样的数据)
        $threeRecord = $threeUser->where([
            ['begin_date', '<=', $endDate],
            ['begin_date', '>=', $startDate]])
            ->where(function ($query) use($endDate){
                $query->where('end_date', '>', $endDate)
                    ->orWhereNull('end_date');
            })
            ->first();

        if (isset($threeRecord)){
            //开始日期为$threeRecord->begin_date，结束日期为$endDate
            $days = $this->detailInDays($threeRecord->begin_date, $endDate, $days);
        }

        return $days;
    }

    private function detailInDays($startDate, $endDate, array $days)
    {
        $holidays = [];
        $workdays = [];
        //假期
        $holidayCollect = Calendar::where([
            ['begin_date', '>=', $startDate],
            ['end_date', '<=', $endDate],
            ['type', '=', 0]
        ])->select('begin_date')->get();
        //补班
        $workdayCollect = Calendar::where([
            ['begin_date', '>=', $startDate],
            ['end_date', '<=', $endDate],
            ['type', '=', 1]
        ])->select('begin_date')->get();

        foreach ($holidayCollect as $holiday){
            array_push($holidays, $holiday->begin_date);
        }

        foreach ($workdayCollect as $workday){
            array_push($workdays, $workday->begin_date);
        }

        $start = Carbon::parse($startDate)->copy();
        $end =  Carbon::parse($endDate)->copy();
        //将范围中属于工作日的日期取出（排除假期日期，加上补班日期）
        for ($day = $start; $day->lte($end); $day->addDay()){
            if ($day->isWeekday()){
                //工作中如果是假期则排除
                if (!empty($holidays)){
                    if (!in_array($day->toDateString(), $holidays)) array_push($days, $day->toDateString());
                } else {
                    array_push($days, $day->toDateString());
                }
            } else {
                //周末中如果是补班则添加
                if (!empty($workdays)){
                    if (in_array($day->toDateString(), $workdays)) array_push($days, $day->toDateString());
                }
            }
        }

        return $days;
    }
}
