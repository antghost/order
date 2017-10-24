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

        $weekdays = $this->diffOfWorkdays($startDate, $endDate);
        $holidays = $this->diffOfHolidays($startDate, $endDate);
        $workday = $weekdays - $holidays;

        $user = Auth::user();
        return view('user.home', [
            'workday' => $workday,
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

    private function bookOfDays($startDate, $endDate, $method = null)
    {
        $user = Auth::user();
        if (strtolower($method) == 'breakfast') {
            $days = $user->bookBreakfasts()->where([
                ['begin_date', '>=', $startDate],
                ['end_date', '<=', $endDate],
            ])->get();
        }
        if (strtolower($method) == 'lunch') {
            $days = $user->bookLunches()->where([
                ['begin_date', '>=', $startDate],
                ['end_date', '<=', $endDate],
            ])->get();
        }
        if ($days($method) == 'dinner') {
            $days = $user->bookDinners()->where([
                ['begin_date', '>=', $startDate],
                ['end_date', '<=', $endDate],
            ])->get();
        }

        $dayCount = 0;

        if ($days->count() > 0){
            foreach ($days as $day){
                $workday = $this->diffOfWorkdays($day->begin_date, $day->end_date);
                $holiday = $this->diffOfHolidays($day->begin_date, $day->end_date);
                //实际天数=工作日天数减去假期天数
                $dayCount = $dayCount + ($workday - $holiday);
            }
        }

        return $dayCount;

    }

    private function cancelOfDays($startDate, $endDate, $method = null)
    {
        $user = Auth::user();
        if (strtolower($method) == 'breakfast') {
            $days = $user->cancelBreakfasts()->where([
                ['begin_date', '>=', $startDate],
                ['end_date', '<=', $endDate],
            ])->get();
        }
        if (strtolower($method) == 'lunch') {
            $days = $user->cancelLunches()->where([
                ['begin_date', '>=', $startDate],
                ['end_date', '<=', $endDate],
            ])->get();
        }
        if ($days($method) == 'dinner') {
            $days = $user->cancelDinners()->where([
                ['begin_date', '>=', $startDate],
                ['end_date', '<=', $endDate],
            ])->get();
        }

        $dayCount = 0;

        if ($days->count() > 0){
            foreach ($days as $day){
                $workday = $this->diffOfWorkdays($day->begin_date, $day->end_date);
                $holiday = $this->diffOfHolidays($day->begin_date, $day->end_date);
                //实际天数=工作日天数减去假期天数
                $dayCount = $dayCount + ($workday - $holiday);
            }
        }

        return $dayCount;

    }
}
