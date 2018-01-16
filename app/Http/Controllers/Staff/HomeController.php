<?php

namespace App\Http\Controllers\Staff;

use App\Models\BookBreakfast;
use App\Models\BookDinner;
use App\Models\BookLunch;
use App\Models\ReportData;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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

    /**
     * echarts柱状图所需要的数据
     * @return array
     *
     */
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

    public function report()
    {
        return view('staff.report.index');
    }

    public function getReportData(Request $request)
    {
        $year = $request->input('year');
        $data = [];

        //对报表数据按月份分组统计早、中、晚餐
        $reportDatas = ReportData::select('month',
            DB::raw('sum(case when breakfasts>0 then 1 else 0 end) as breakfast_users,
            sum(case when lunches>0 then 1 else 0 end) as lunch_users,
            sum(case when dinners>0 then 1 else 0 end) as dinner_users,
            sum(breakfasts) as breakfasts,sum(lunches) as lunches,sum(dinners) as dinners,
            sum(breakfast_amount) as breakfast_amount,sum(lunch_amount) as lunch_amount,sum(dinner_amount) as dinner_amount'))
            ->where('year', $year) //->where('breakfasts', '>', 0)
            ->groupBy('month')->orderBy('month')->get();

        foreach ($reportDatas as $reportData) {
            //早餐用餐人数
            $breakfastUsers[] = $reportData->breakfast_users;
            //午餐用餐人数
            $lunchUsers[] = $reportData->lunch_users;
            //晚餐用餐人数
            $dinnerUsers[] = $reportData->dinner_users;
            //早餐月用餐总人数
            $breakfasts[] = $reportData->breakfasts;
            //午餐月用餐总人数
            $lunches[] = $reportData->lunches;
            //晚餐月用餐总人数
            $dinners[] = $reportData->dinners;
            //早餐月用餐总金额
            $breakfastAmount[] = $reportData->breakfast_amount;
            //午餐月用餐总金额
            $lunchAmount[] = $reportData->lunch_amount;
            //晚餐月用餐总金额
            $dinnerAmount[] = $reportData->dinner_amount;
        }
        $data['breakfast_users'] = $breakfastUsers;
        $data['lunch_users'] = $lunchUsers;
        $data['dinner_users'] = $dinnerUsers;
        $data['breakfasts'] = $breakfasts;
        $data['lunches'] = $lunches;
        $data['dinners'] = $dinners;
        $data['breakfast_amount'] = $breakfastAmount;
        $data['lunch_amount'] = $lunchAmount;
        $data['dinner_amount'] = $dinnerAmount;
        return $data;
    }

}
