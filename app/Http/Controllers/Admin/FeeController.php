<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Price;

class FeeController extends Controller
{
    public function index()
    {
        return view('admin.fee.index', ['prices' => Price::all()]);
    }

    public function create()
    {
        return view('admin.fee.create');
    }

    public function store(Request $request)
    {
        $request->flash();
        $this->validate($request,[
            'name' => 'required|string|unique:prices',
            'breakfast' => 'required|numeric|min:0|max:100',
            'lunch' => 'required|numeric|min:0|max:100',
            'dinner' => 'required|numeric|min:0|max:100',
            'begin_date' => 'nullable|date|after:today'
        ]);

        $price = Price::create([
            'name' => $request->input('name'),
            'breakfast' => $request->input('breakfast'),
            'lunch' => $request->input('lunch'),
            'dinner' => $request->input('dinner'),
            'begin_date' => $request->input('begin_date'),
            'status' => '1',
        ]);

        if(isset($price)){
            return redirect('admin/fee');
        } else {
            return redirect()->back()->withInput()->withErrors('提交失败');
        }
    }
}
