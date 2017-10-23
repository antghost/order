<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Calendar;

class CalendarController extends Controller
{
    public function index()
    {
        return view('common.calendar.index', ['calendars' => Calendar::paginate(15)]);
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

        $calendar = Calendar::create([
            'name' => $name,
            'begin_date' => $beginDate,
            'end_date' => $endDate,
            'type' => $type,
            'user_id' => $userId,
        ]);

        return redirect()->back();
    }
}
