<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Menu;

class IndexController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        //当天用餐人数
        $userInBreakfasts = $this->userOfDays($today, $today, 'breakfast');
        $userInLunches = $this->userOfDays($today, $today, 'lunch');
        $userInDinners = $this->userOfDays($today, $today, 'dinner');
        $breakfastMenus = Menu::where('type', 1)->where('active', 1)->get();
        $lunchMenus = Menu::where('type', 2)->where('active', 1)->get();
        $dinnerMenus = Menu::where('type', 3)->where('active', 1)->get();
        //通知
        $notices = Notice::all();
        return view('index',
            compact('userInBreakfasts', 'userInLunches', 'userInDinners', 'breakfastMenus', 'lunchMenus', 'dinnerMenus',
                'notices')
        );
    }

    public function getMenu()
    {}
}
