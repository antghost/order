<?php

namespace App\Http\Controllers\Staff;

use App\Models\BookBreakfast;
use App\Models\BookDinner;
use App\Models\BookLunch;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('staff.home');
    }

    public function getData()
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        //当天用餐人数
        $userOfDayInBreakfasts = $this->userOfDays($today, $today, 'breakfast');
        $userOfDayInLunches = $this->userOfDays($today, $today, 'lunch');
        $userOfDayInDinners = $this->userOfDays($today, $today, 'dinner');

        //明天用餐人数
        $userOfTomorrowInBreakfasts = $this->userOfDays($tomorrow, $tomorrow, 'breakfast');
        $userOfTomorrowInLunches = $this->userOfDays($tomorrow, $tomorrow, 'lunch');
        $userOfTomorrowInDinners = $this->userOfDays($tomorrow, $tomorrow, 'dinner');

        //本周用餐人数
        $startDate = $today->copy()->startOfWeek();
        $endDate = $startDate->copy()->addDays(4);

        $userOfWeekInBreakfasts = $this->userOfDays($startDate, $endDate, 'breakfast');
        $userOfWeekInLunches = $this->userOfDays($startDate, $endDate, 'lunch');
        $userOfWeekInDinners = $this->userOfDays($startDate, $endDate, 'dinner');

        //本月用餐人数
        $startDate = $today->copy()->startOfMonth();
        $endDate = $today->copy()->endOfMonth();
        $userOfMonthInBreakfasts = $this->userOfDays($startDate, $endDate, 'breakfast');
        $userOfMonthInLunches = $this->userOfDays($startDate, $endDate, 'lunch');
        $userOfMonthInDinners = $this->userOfDays($startDate, $endDate, 'dinner');

        $data = [];
        $data = [
            'breakfasts' => [$userOfMonthInBreakfasts, $userOfWeekInBreakfasts, $userOfTomorrowInBreakfasts, $userOfDayInBreakfasts],
            'lunches' => [$userOfMonthInLunches, $userOfWeekInLunches, $userOfTomorrowInLunches, $userOfDayInLunches],
            'dinners' => [$userOfMonthInDinners, $userOfWeekInDinners, $userOfTomorrowInDinners, $userOfDayInDinners]
            ];
        return $data;
//        return view('staff.home', [
//            'userOfDayInBreakfasts' => $userOfDayInBreakfasts,
//            'userOfDayInLunches' => $userOfDayInLunches,
//            'userOfDayInDinners' => $userOfDayInDinners,
//            'userOfTomorrowInBreakfasts' => $userOfTomorrowInBreakfasts,
//            'userOfTomorrowInLunches' => $userOfTomorrowInLunches,
//            'userOfTomorrowInDinners' => $userOfTomorrowInDinners,
//            'userOfWeekInBreakfasts' => $userOfWeekInBreakfasts,
//            'userOfWeekInLunches' => $userOfWeekInLunches,
//            'userOfWeekInDinners' => $userOfWeekInDinners,
//            'userOfMonthInBreakfasts' => $userOfMonthInBreakfasts,
//            'userOfMonthInLunches' => $userOfMonthInLunches,
//            'userOfMonthInDinners' => $userOfMonthInDinners,
//        ]);
    }

    private function userOfDays($startDate, $endDate, $method = null)
    {
        if (strtolower($method) == 'breakfast') $meal = new BookBreakfast();
        if (strtolower($method) == 'lunch') $meal = new BookLunch();
        if (strtolower($method) == 'dinner') $meal = new BookDinner();

        $userCount = 0;
        //情况1：$startDate：10月1日，$endDate：10月31日，数据记录开始与结束日期都为10月
        $oneCount = $meal::where([
            ['begin_date', '>=', $startDate],
            ['end_date', '<=', $endDate],
        ])->count();

        //情况2：$startDate：10月1日，$endDate：10月31日，数据记录开始日期为9月或之前，结束日期为10月或之后(理论上应该只有1条这样的数据)
        $twoCount = $meal::where('begin_date', '<', $startDate)
            ->where(function ($query) use ($startDate){
                $query->where('end_date', '>=', $startDate)
                    ->orWhereNull('end_date');
            })
            ->count();

        //情况3：$startDate：10月1日，$endDate：10月31日，数据记录开始日期为10月，结束日期为11月或之后(理论上应该只有1条这样的数据)
        $threeCount = $meal::where([
            ['begin_date', '<=', $endDate],
            ['begin_date', '>=', $startDate]])
            ->where(function ($query) use($endDate){
                $query->where('end_date', '>', $endDate)
                    ->orWhereNull('end_date');
            })
            ->count();

        return $userCount = $oneCount + $twoCount + $threeCount;
    }
}
