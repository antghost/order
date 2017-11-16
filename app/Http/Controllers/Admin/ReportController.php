<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ReportData;
use App\User;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.report.index');
    }

    public function setData(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        $dt = Carbon::create($year, $month);
        $startDate = $dt->copy()->startOfMonth();
        $endDate = $dt->copy()->endOfMonth();
        $reportData = new ReportData();
        $num = $reportData->userBookDays($year, $month, $startDate, $endDate);
        return Redirect()->back()->with('status', '成功处理'.$num.'条记录。');
    }
}
