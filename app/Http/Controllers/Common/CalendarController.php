<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Calendar;

class CalendarController extends Controller
{
    public function index()
    {
        return view('common.calendar.index', [
            'calendars' => Calendar::orderBy('begin_date')->paginate(15)
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'begin_date' => 'required|date',
            'end_date' => 'required|date',
            'type' => 'required',
        ]);

        $name = $request->input('name');
        $beginDate = $request->input('begin_date');
        $endDate = $request->input('end_date');
        $type = $request->input('type');
        $userId = Auth::user()->id;

        //将时间段分解为每一天插入到数据表中，便于某时间段假期或补班的统计
        $beginDate = Carbon::parse($beginDate);
        $endDate = Carbon::parse($endDate);
        $num = $endDate->diffInDays($beginDate);
        //$beginDate先减去一天用于下面的循环
        $beginDate->subDay();
        for ($i = 0 ;$i <= $num; $i++) {
            $date = $beginDate->addDays(1);
            $calendar = Calendar::create([
                'name' => $name,
                'begin_date' => $date,
                'end_date' => $date,
                'type' => $type,
                'user_id' => $userId,
            ]);
        }

        return redirect()->back();
    }
}
