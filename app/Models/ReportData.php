<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Calendar;
use App\User;

class ReportData extends Model
{
    protected $fillable = ['user_id', 'year', 'month', 'breakfasts', 'lunches', 'dinners',
        'breakfast_price', 'lunch_price', 'dinner_price', 'breakfast_amount', 'lunch_amount', 'dinner_amount'];


    public function userBookDays($year, $month, $startDate, $endDate)
    {
        self::where([['year', $year], ['month' , $month]])->delete();
        $users = User::all();
        $i = 0;
        foreach ($users as $user){
            $breakfasts = $this->bookOrCancelDays($user, $startDate, $endDate, 'breakfast');
            $lunches = $this->bookOrCancelDays($user, $startDate, $endDate, 'lunch');
            $dinners = $this->bookOrCancelDays($user, $startDate, $endDate, 'dinner');
            $breakfastPrice = $user->price->breakfast;
            $lunchPrice = $user->price->lunch;
            $dinnerPrice = $user->price->dinner;
            $breakfastAmount = bcmul($breakfasts, $breakfastPrice, 2);
            $lunchAmount = bcmul($lunches, $lunchPrice, 2);
            $dinnerAmount = bcmul($dinners, $dinnerPrice, 2);
            self::create([
                'user_id' => $user->id,
                'year' => $year,
                'month' => $month,
                'breakfasts' => $breakfasts,
                'lunches' => $lunches,
                'dinners' => $dinners,
                'breakfast_price' => $breakfastPrice,
                'lunch_price' => $lunchPrice,
                'dinner_price' => $dinnerPrice,
                'breakfast_amount' => $breakfastAmount,
                'lunch_amount' => $lunchAmount,
                'dinner_amount' => $dinnerAmount,
            ]);
            $i++;
        }
        return $i;
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
     * 统计两时间段之间的假期天数（正数）或补班天数（负数）
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
                $sDate = $holiday->begin_date;
                $eDate = $holiday->end_date;
                //统计假期中的工作日天数
                $holidayCount = $holidayCount + $this->diffOfWorkdays($sDate, $eDate);
            }
        }

        if ($workdays->count() > 0 ){
            foreach ($workdays as $workday){
                $sDate = $workday->begin_date;
                $eDate = $workday->end_date;
                //统计补班中的周末天数
                $workdayCount = $workdayCount + $this->diffOfWorkdays($sDate, $eDate, false);
            }
        }

        return $holidayCount - $workdayCount ;
    }

    private function bookOrCancelDays(User $user,$startDate, $endDate, $method = null, $type = 'book')
    {
        if (strtolower($type) == 'book'){
            if (strtolower($method) == 'breakfast') {
                $oneUser = $user->bookBreakfasts();
                $twoUser = $user->bookBreakfasts();
                $threeUser = $user->bookBreakfasts();
            }
            if (strtolower($method) == 'lunch') {
                $oneUser = $user->bookLunches();
                $twoUser = $user->bookLunches();
                $threeUser = $user->bookLunches();
            }
            if (strtolower($method) == 'dinner') {
                $oneUser = $user->bookDinners();
                $twoUser = $user->bookDinners();
                $threeUser = $user->bookDinners();
            }
        }
        if (strtolower($type) == 'cancel') {
            if (strtolower($method) == 'breakfast') {
                $oneUser = $user->cancelBreakfasts();
                $twoUser = $user->cancelBreakfasts();
                $threeUser = $user->cancelBreakfasts();
            }
            if (strtolower($method) == 'lunch') {
                $oneUser = $user->cancelLunches();
                $twoUser = $user->cancelLunches();
                $threeUser = $user->cancelLunches();
            }
            if (strtolower($method) == 'dinner') {
                $oneUser = $user->cancelDinners();
                $twoUser = $user->cancelDinners();
                $threeUser = $user->cancelDinners();
            }
        }

        $dayCount = 0;
        //情况1：$startDate：10月1日，$endDate：10月31日，数据记录开始与结束日期都为10月
        $oneRecords = $oneUser->where([
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

            $workday = $this->diffOfWorkdays($startDate, $twoEndDate);
            $holiday = $this->diffOfHolidays($startDate, $twoEndDate);
            //实际天数=工作日天数减去假期天数
            $dayCount = $dayCount + ($workday - $holiday);
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
            $workday = $this->diffOfWorkdays($threeRecord->begin_date, $endDate);
            $holiday = $this->diffOfHolidays($threeRecord->begin_date, $endDate);
            //实际天数=工作日天数减去假期天数
            $dayCount = $dayCount + ($workday - $holiday);
        }

        return $dayCount;

    }
}
