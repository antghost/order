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
        $notices = Notice::orderby('valid_date','desc')->get();
        return view('index',
            compact('userInBreakfasts', 'userInLunches', 'userInDinners', 'breakfastMenus', 'lunchMenus', 'dinnerMenus',
                'notices')
        );
    }

    /**
     * 通知显示
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function notice($id)
    {
        $notice = Notice::findOrFail($id);
        return view('common.notice.show', compact('notice'));
    }

    /**
     * 首页ajax获取菜单
     * @return array
     */
    public function getMenu()
    {
        $breakfastMenus = Menu::where('type', 1)->where('active', 1)->get();
        $lunchMenus = Menu::where('type', 2)->where('active', 1)->get();
        $dinnerMenus = Menu::where('type', 3)->where('active', 1)->get();
        $data = [];
        $data = [
            'breakfast' => $breakfastMenus,
            'lunch' => $lunchMenus,
            'dinner' => $dinnerMenus,
        ];
        return $data;
    }

    /**
     * 首页ajax获取就餐人数
     * @return array
     */
    public function getUserNum()
    {
        $today = Carbon::today();
        //当天用餐人数
        $userInBreakfasts = $this->userOfDays($today, $today, 'breakfast');
        $userInLunches = $this->userOfDays($today, $today, 'lunch');
        $userInDinners = $this->userOfDays($today, $today, 'dinner');
        $data = [];
        $data = [
            'breakfast' => $userInBreakfasts,
            'lunch' => $userInLunches,
            'dinner' => $userInDinners,
//            'breakfasts' => random_int(100,300),
//            'lunches' => random_int(100,300),
//            'dinners' => random_int(100,300),
        ];
        return $data;
    }
}
