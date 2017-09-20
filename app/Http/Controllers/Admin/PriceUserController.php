<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Price;

class PriceUserController extends Controller
{
    public function edit($id)
    {
        $user = User::findOrFail($id);
        //收费标准个人历史
        $priceUsers = $user->priceUsers()->orderBy('begin_date')->paginate(15);
        return view('admin.user.price', ['user' => $user, 'priceUsers' => $priceUsers, 'prices' => Price::all()]);
    }
}
