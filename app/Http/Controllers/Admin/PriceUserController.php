<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\User;
use App\Models\Price;
use App\Models\PriceUser;

class PriceUserController extends Controller
{
    /**
     * 显示编辑个人收费标准页面
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        //收费标准个人历史
        $priceUsers = $user->priceUsers()->orderBy('begin_date')->paginate(15);
        return view('admin.user.price', [
            'user' => $user,
            'priceUsers' => $priceUsers,
            'prices' => Price::all()
        ]);
    }

    /**
     * 更新个人收费标准
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'begin_date' => 'required|date',
        ]);
        $price_id = $request->input('price');
        $user = User::findOrFail($id);
        //用户收费标准未变化不作修改
        if ($price_id == $user->price_id){
            return redirect()->back()->withErrors('标准未更改，不作修改。')->withInput();
        }

        //收费标准
        $price = Price::findOrFail($price_id);
        //用户收费标准历史
        $priceUsers = $user->priceUsers()->whereNull('valid_date');
        //开始日期
        $begin_date = $request->input('begin_date');
        //旧标准失效日期为新标准开始日期的前一天
        $valid_date = Carbon::parse($begin_date)->subDay();

        //如果用户收费标准历史为空时则添加，否则先失效再添加
        DB::beginTransaction();
        //存在旧标准时将其设置为失效
        if(!$priceUsers->get()->isEmpty()) {
            $priceUsers->update(['valid_date' => $valid_date]);
        }

        $user->update(['price_id' => $price_id]);
        $priceUser = PriceUser::create([
            'begin_date' => $begin_date,
            'user_id' => $user->id,
            'price_id' => $price_id,
            'breakfast' => $price->breakfast,
            'lunch' => $price->lunch,
            'dinner' => $price->dinner,
        ]);
        if (isset($priceUser)){
            DB::commit();
            return redirect()->back()->with('status', '修改完成');
        } else {
            DB::rollBack();
            return redirect()->back()->withErrors('修改失败')->withInput();
        }

        return redirect()->back()->withErrors('修改失败')->withInput();

    }
}
