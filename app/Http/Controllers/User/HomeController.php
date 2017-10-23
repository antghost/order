<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;

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
        $workday = $this->diffOfDays('2017-10-9','2017-10-12', false);
        return view('user.home');
    }

    /**
     * 统计两个时间段之间工作日天数或周末天数
     * @param $startDate 开始日期
     * @param $endDay  结束日期
     * @param bool $isWorkday  true:工作日 ,false:周末
     * @return int
     */
    private function diffOfDays($startDate, $endDay, $isWorkday = true)
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
}
