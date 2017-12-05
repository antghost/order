<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\BookBreakfast;
use App\Models\BookLunch;
use App\Models\BookDinner;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function userOfDays($startDate, $endDate, $method = null)
    {
        if (strtolower($method) == 'breakfast') $meal = new BookBreakfast();
        if (strtolower($method) == 'lunch') $meal = new BookLunch();
        if (strtolower($method) == 'dinner') $meal = new BookDinner();

        $userCount = 0;
        //情况1：$startDate：10月1日，$endDate：10月31日，数据记录开始与结束日期都为10月
        $oneCount = $meal::select('user_id')->where([
            ['begin_date', '>=', $startDate],
            ['end_date', '<=', $endDate],
        ]);
//            ->distinct('user_id')->count('user_id');

        //情况2：$startDate：10月1日，$endDate：10月31日，数据记录开始日期为9月或之前，结束日期为10月或之后(理论上应该只有1条这样的数据)
        $twoCount = $meal::select('user_id')
            ->where('begin_date', '<', $startDate)
            ->where(function ($query) use ($startDate){
                $query->where('end_date', '>=', $startDate)
                    ->orWhereNull('end_date');
            });
//            ->distinct('user_id')->count('user_id');

        //情况3：$startDate：10月1日，$endDate：10月31日，数据记录开始日期为10月，结束日期为11月或之后(理论上应该只有1条这样的数据)
        $threeCount = $meal::select('user_id')->where([
            ['begin_date', '<=', $endDate],
            ['begin_date', '>=', $startDate]])
            ->where(function ($query) use($endDate){
                $query->where('end_date', '>', $endDate)
                    ->orWhereNull('end_date');
            })->union($twoCount)->union($oneCount)->get();
//            ->distinct('user_id')->count('user_id');
        $userCount = $threeCount->count();

        return $userCount;
    }
}
