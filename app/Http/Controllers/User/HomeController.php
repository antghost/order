<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Models\Calendar;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 显示概况信息
     */
    public function index()
    {
        //统计当月用餐天数
        $startDate = Carbon::today()->startOfMonth()->toDateString();
        $endDate = Carbon::today()->toDateString();

        //工作日天数
        $weekdays = $this->diffOfWorkdays($startDate, $endDate);
        //假期或补班天数
        $holidays = $this->diffOfHolidays($startDate, $endDate);
        //实际工作天数
        $workday = $weekdays - $holidays;

        $user = Auth::user();

        //早餐用餐天数
        if ($user->userOrderStatuses->breakfast){
            $cancelDays = $this->bookOrCancelDays($startDate, $endDate, 'breakfast', 'cancel');
            $breakfastDays = $workday - $cancelDays;
        } else {
            $bookDays = $this->bookOrCancelDays($startDate, $endDate, 'breakfast', 'book');
            $breakfastDays = $bookDays;
        }
        //早餐用餐总额
        $breakfastAmount = $breakfastDays * $user->price->breakfast;

        //午餐用餐天数
        if ($user->userOrderStatuses->lunch){
            $cancelDays = $this->bookOrCancelDays($startDate, $endDate, 'lunch', 'cancel');
            $lunchDays = $workday - $cancelDays;
        } else {
            $bookDays = $this->bookOrCancelDays($startDate, $endDate, 'lunch', 'book');
            $lunchDays = $bookDays;
        }
        //午餐用餐总额
        $lunchAmount = $lunchDays * $user->price->lunch;

        //晚餐用餐天数
        if ($user->userOrderStatuses->dinner){
            $cancelDays = $this->bookOrCancelDays($startDate, $endDate, 'dinner', 'cancel');
            $dinnerDays = $workday - $cancelDays;
        } else {
            $bookDays = $this->bookOrCancelDays($startDate, $endDate, 'dinner', 'book');
            $dinnerDays = $bookDays;
        }
        //晚餐用餐总额
        $dinnerAmount = $dinnerDays * $user->price->breakfast;

        $totalAmount = $breakfastAmount + $lunchAmount + $dinnerAmount;

        return view('user.home', [
            'breakfastDays' => $breakfastDays,
            'breakfastAmount' => sprintf("%01.2f", $breakfastAmount),
            'lunchDays' => $lunchDays,
            'lunchAmount' => sprintf("%01.2f", $lunchAmount),
            'dinnerDays' => $dinnerDays,
            'dinnerAmount' => sprintf("%01.2f", $dinnerAmount),
            'totalAmount' => sprintf("%01.2f", $totalAmount),
        ]);
    }

    /**
     * 统计两个时间段之间工作日天数或周末天数
     * @param $startDate 开始日期
     * @param $endDay  结束日期
     * @param bool $isWorkday  true:工作日 ,false:周末
     * @return int
     */
    private function diffOfWorkdays($startDate, $endDay, $isWorkday = true)
    {
        $startDate = Carbon::parse($startDate);
        $endDay = Carbon::parse($endDay);
        //Carbon默认不含最后一天，现修改为包括最后一天。
        if ($isWorkday){
            $days = $endDay->diffInWeekdays($startDate);
            if ($endDay->isWeekday()) $days = $days + 1;
        } else {
            $days = $endDay->diffInWeekendDays($startDate);
            if ($endDay->isWeekend()) $days = $days + 1;
        }
        return $days;
    }

    /**
     * 统计两时间段之间的假期天数
     * @param $startDate
     * @param $endDate
     * @return int
     */
    private function diffOfHolidays($startDate, $endDate)
    {
        //假期
        $holidays = Calendar::where([
            ['begin_date', '>=', $startDate],
            ['end_date', '<=', $endDate],
            ['type', '=', 0]
        ])->get();
        //补班
        $workdays = Calendar::where([
            ['begin_date', '>=', $startDate],
            ['end_date', '<=', $endDate],
            ['type', '=', 1]
        ])->get();

        $holidayCount = 0;
        $workdayCount = 0;

        if ($holidays->count() > 0 ){
            foreach ($holidays as $holiday){
                $sDate = Carbon::parse($holiday->begin_date);
                $eDate = Carbon::parse($holiday->end_date);
                //统计假期中的工作日天数
                $holidayCount = $holidayCount + $this->diffOfWorkdays($sDate, $eDate);
            }
        }

        if ($workdays->count() > 0 ){
            foreach ($workdays as $workday){
                $sDate = Carbon::parse($workday->begin_date);
                $eDate = Carbon::parse($workday->end_date);
                //统计补班中的周末天数
                $workdayCount = $workdayCount + $this->diffOfWorkdays($sDate, $eDate, false);
            }
        }

        return $holidayCount - $workdayCount ;
    }

    private function bookOrCancelDays($startDate, $endDate, $method = null, $type = 'book')
    {
        if ($type == 'book'){
            if (strtolower($method) == 'breakfast') {
                $user = Auth::user()->bookBreakfasts();
                $twoUser = Auth::user()->bookBreakfasts();
                $threeUser = Auth::user()->bookBreakfasts();
            }
            if (strtolower($method) == 'lunch') {
                $user = Auth::user()->bookLunches();
                $twoUser = Auth::user()->bookLunches();
                $threeUser = Auth::user()->bookLunches();
            }
            if (strtolower($method) == 'dinner') {
                $user = Auth::user()->bookDinners();
                $twoUser = Auth::user()->bookDinners();
                $threeUser = Auth::user()->bookDinners();
            }
        }
        if ($type == 'cancel') {
            if (strtolower($method) == 'breakfast') {
                $user = Auth::user()->cancelBreakfasts();
                $twoUser = Auth::user()->cancelBreakfasts();
                $threeUser = Auth::user()->cancelBreakfasts();
            }
            if (strtolower($method) == 'lunch') {
                $user = Auth::user()->cancelLunches();
                $twoUser = Auth::user()->cancelLunches();
                $threeUser = Auth::user()->cancelLunches();
            }
            if (strtolower($method) == 'dinner') {
                $user = Auth::user()->cancelDinners();
                $twoUser = Auth::user()->cancelDinners();
                $threeUser = Auth::user()->cancelDinners();
            }
        }

        $dayCount = 0;
        //情况1：$startDate：10月1日，$endDate：10月31日，数据记录开始与结束日期都为10月
        $oneRecords = $user->where([
            ['begin_date', '>=', $startDate],
            ['end_date', '<=', $endDate],
        ])->get();

        if ($oneRecords->count() > 0){
            foreach ($oneRecords as $oneRecord){
                $workday = $this->diffOfWorkdays($oneRecord->begin_date, $oneRecord->end_date);
                $holiday = $this->diffOfHolidays($oneRecord->begin_date, $oneRecord->end_date);
                //实际天数=工作日天数减去假期天数
                $dayCount = $dayCount + ($workday - $holiday);
            }
        }
//        var_dump('$dayCount:'.$dayCount);

        //情况2：$startDate：10月1日，$endDate：10月31日，数据记录开始日期为9月或之前，结束日期为10月或之后(理论上应该只有1条这样的数据)
        $twoRecord = $twoUser->where([
            ['begin_date', '<=', $startDate],
            ['end_date', '>=', $startDate],
        ])->first();

        //dd($twoRecord);

        if (isset($twoRecord)){
            //开始日期为$startDate，结束日期为$twoRecord->end_date
            ($twoRecord->end_date >= $endDate) ? $twoEndDate = $endDate : $twoEndDate = $twoRecord->end_date;
            $workday = $this->diffOfWorkdays($startDate, $twoEndDate);
            $holiday = $this->diffOfHolidays($startDate, $twoEndDate);
            //实际天数=工作日天数减去假期天数
            $dayCount = $dayCount + ($workday - $holiday);
//            var_dump('$workday:'.$workday.'$holiday:'.$holiday.'$dayCount:'.$dayCount);

        }

        //情况3：$startDate：10月1日，$endDate：10月31日，数据记录开始日期为10月，结束日期为11月或之后(理论上应该只有1条这样的数据)
        $threeRecord = $threeUser->where([
            ['begin_date', '<=', $endDate],
            ['begin_date', '>=', $startDate],
            ['end_date', '>=', $endDate],
        ])->first();

        //dd($threeRecord);

        if (isset($threeRecord)){
            //开始日期为$threeRecord->begin_date，结束日期为$endDate
            $workday = $this->diffOfWorkdays($threeRecord->begin_date, $endDate);
            $holiday = $this->diffOfHolidays($threeRecord->begin_date, $endDate);
            //实际天数=工作日天数减去假期天数
            $dayCount = $dayCount + ($workday - $holiday);
//            var_dump('$workday:'.$workday.'$holiday:'.$holiday.'$dayCount:'.$dayCount);

        }

        return $dayCount;

    }
}
