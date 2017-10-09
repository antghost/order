<?php

namespace App\Http\Controllers\staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Dept;
use App\User;
use App\Models\UserOrderStatus;

class OrderController extends Controller
{
    /**
     * 首页
     */
    public function index()
    {
        return view('staff.order.index', [ 'depts' => Dept::all(), 'users' => User::paginate(15)]);
    }

    /**
     * 用户搜索功能
     */
    public function search(Request $request)
    {
        $request->flash();
        $dept_id = $request->input('dept');
        $name = $request->input('name');

        //查询条件
        $users = User::when($dept_id, function ($query) use ($dept_id){
            return $query->whereHas('dept', function ($query) use ($dept_id){
                $query->where('id', $dept_id);
            });
        })->when($name, function ($query) use ($name){
            return $query->where('name', 'like', '%'.$name.'%');
        })->paginate(15);

        //分页查询参数
        $users = $users->appends([
            'name' => $name,
            'dept' => $dept_id,
        ]);

        return view('staff.order.index', ['users' => $users, 'depts' => Dept::all()]);
    }

    /**
     *显示编辑页面
     *
    create view v_breakfasts as
    select user_id,begin_date, end_date, '开餐' as type from book_breakfasts
    union
    select user_id,begin_date, end_date, '停餐' as type from cancel_breakfasts
    ;

    create view v_lunches as
    select user_id,begin_date, end_date, '开餐' as type from book_lunches
    union
    select user_id,begin_date, end_date, '停餐' as type from cancel_lunches
    ;

    create view v_dinners as
    select user_id,begin_date, end_date, '开餐' as type from book_dinners
    union
    select user_id,begin_date, end_date, '停餐' as type from cancel_dinners
    ;
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $breakfasts = DB::table('v_breakfasts')->where('user_id',$id)->orderBy('begin_date')->get();
        $lunches = DB::table('v_lunches')->where('user_id',$id)->orderBy('begin_date')->get();
        $dinners = DB::table('v_dinners')->where('user_id',$id)->orderBy('begin_date')->get();
        return view('staff.order.edit', [
            'user' => $user,
            'breakfasts' => $breakfasts,
            'lunches' => $lunches,
            'dinners' => $dinners,
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'breakfast_begin_date' => 'required|date|after:yesterday',
            'lunch_begin_date' => 'required|date|after:yesterday',
            'dinner_begin_date' => 'required|date|after:yesterday',
        ]);
        //早餐状态
        $breakfast = $request->input('breakfast_chk') ? 1 : 0;
        $breakfastBeginDate = $request->input('breakfast_begin_date');
        //午餐状态
        $lunch = $request->input('lunch_chk') ? 1 : 0;
        $lunchBeginDate = $request->input('lunch_begin_date');
        //晚餐状态
        $dinner = $request->input('dinner_chk') ? 1 : 0;
        $dinnerBeginDate = $request->input('dinner_begin_date');

        $breakfastEndDate = Carbon::parse($breakfastBeginDate)->subDay();
        $lunchEndDate = Carbon::parse($lunchBeginDate)->subDay();
        $dinnerEndDate = Carbon::parse($dinnerBeginDate)->subDay();

        $user = User::findOrFail($id);

//        dd('userOrderStatuses:',isset($user->userOrderStatuses),
//            'bookBreakfast:',!$user->bookBreakfasts()->get()->isEmpty()
//            ,'cancelBreakfast:',!$user->cancelBreakfasts()->get()->isEmpty()
//            ,'bookLunch:',!$user->bookLunches()->get()->isEmpty()
//            ,'cancelLunch:',!$user->cancelLunches()->get()->isEmpty()
//            ,'bookDinner:',!$user->bookDinners()->get()->isEmpty()
//            ,'cancelDinner:',!$user->cancelDinners()->get()->isEmpty()
//        );

        DB::beginTransaction();

        //停开餐处理
        if ( isset($user->userOrderStatuses)
            && ( !$user->bookBreakfasts()->get()->isEmpty() || !$user->cancelBreakfasts()->get()->isEmpty() )
            && ( !$user->bookLunches()->get()->isEmpty() || !$user->cancelLunches()->get()->isEmpty() )
            && ( !$user->bookDinners()->get()->isEmpty() || !$user->cancelDinners()->get()->isEmpty() )){
            //早餐开餐状态变停餐状态处理
            if ($user->userOrderStatuses->breakfast && empty($breakfast)) {
                //失效开餐记录
                $user->bookBreakfast()->whereNull('end_date')->update(['end_date' => $breakfastEndDate]);
                //增加停餐记录
                $cancelBreakfast = $user->cancelBreakfasts()->create([
                    'begin_date' => $breakfastBeginDate,
                ]);
            }
            //早餐停餐状态变开餐状态处理
            if (!$user->userOrderStatuses->breakfast && !empty($breakfast)) {
                //失效停餐记录
                $user->cancelBreakfasts()->whereNull('end_date')->update(['end_date' => $breakfastEndDate]);
                //增加开餐记录
                $bookBreakfast = $user->bookBreakfasts()->create([
                    'begin_date' => $breakfastBeginDate,
                ]);
            }
            //午餐开餐状态变停餐状态处理
            if ($user->userOrderStatuses->lunch && empty($lunch)) {
                //失效开餐记录
                $user->bookLunches()->whereNull('end_date')->update(['end_date' => $lunchEndDate]);
                //增加停餐记录
                $cancelLunch = $user->cancelLunches()->create([
                    'begin_date' => $lunchBeginDate,
                ]);
            }
            //午餐停餐状态变开餐状态处理
            if (!$user->userOrderStatuses->lunch && !empty($lunch)) {
                //失效停餐记录
                $user->cancelLunches()->whereNull('end_date')->update(['end_date' => $lunchEndDate]);
                //增加开餐记录
                $bookLunch = $user->bookLunches()->create([
                    'begin_date' => $lunchBeginDate,
                ]);
            }
            //晚餐开餐状态变停餐状态处理
            if ($user->userOrderStatuses->dinner && empty($dinner)) {
                //失效开餐记录
                $user->bookDinners()->whereNull('end_date')->update(['end_date' => $dinnerEndDate]);
                //增加停餐记录
                $cancelDinner = $user->cancelDinners()->create([
                    'begin_date' => $dinnerBeginDate,
                ]);
            }
            //晚餐停餐状态变开餐状态处理
            if (!$user->userOrderStatuses->dinner && !empty($dinner)) {
                //失效停餐记录
                $user->cancelDinners()->whereNull('end_date')->update(['end_date' => $dinnerEndDate]);
                //增加开餐记录
                $bookDinner = $user->bookDinners()->create([
                    'begin_date' => $dinnerBeginDate,
                ]);
            }

        } else {
            //早餐停开餐处理
            if ($breakfast) {
                //增加开餐记录
                $bookBreakfast = $user->bookBreakfasts()->create([
                    'begin_date' => $breakfastBeginDate,
                ]);
            } else {
                //增加停餐记录
                $cancelBreakfast = $user->cancelBreakfasts()->create([
                    'begin_date' => $breakfastBeginDate,
                ]);
            }
            //午餐停开餐处理
            if ($breakfast) {
                //增加开餐记录
                $bookLunch = $user->bookLunches()->create([
                    'begin_date' => $lunchBeginDate,
                ]);
            } else {
                //增加停餐记录
                $cancelLunch = $user->cancelLunches()->create([
                    'begin_date' => $lunchBeginDate,
                ]);
            }
            //晚餐停开餐处理
            if ($dinner) {
                //增加开餐记录
                $bookDinner = $user->bookDinners()->create([
                    'begin_date' => $dinnerBeginDate,
                ]);
            } else {
                //增加停餐记录
                $cancelDinner = $user->cancelDinners()->create([
                    'begin_date' => $dinnerBeginDate,
                ]);
            }
        }

        $userOrderStatuses = UserOrderStatus::updateOrCreate(
        //第一个参数数组中必须是unique,否则更新将失效
            ['user_id' => $id],
            //第二个参数为更新/创建的值
            ['breakfast' => $breakfast, 'lunch' => $lunch, 'dinner' => $dinner]
        );

        if (isset($userOrderStatuses)
            && (isset($bookBreakfast) || isset($cancelBreakfast)
             || isset($bookLunch) || isset($cancelLunch)
             || isset($bookDinner) || isset($cancelDinner))){
            DB::commit();
            return redirect()->back()->with('status', '处理完成');
        } else {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors('提交失败');
        }

    }

}
