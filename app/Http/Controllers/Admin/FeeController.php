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

    public function edit($id)
    {
        return view('admin.fee.edit', ['price' => Price::find($id)]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name' => 'required|string|unique:prices,name,'.$id,
            'breakfast' => 'required|numeric|min:0|max:100',
            'lunch' => 'required|numeric|min:0|max:100',
            'dinner' => 'required|numeric|min:0|max:100',
            'begin_date' => 'nullable|date|after:today'
        ]);

        $price = Price::findOrFail($id);
        $price->name = $request->input('name');
        $price->breakfast = $request->input('breakfast');
        $price->lunch = $request->input('lunch');
        $price->dinner = $request->input('dinner');
        $price->begin_date = $request->input('begin_date');

        if($price->save()){
            return redirect()->back()->with('status', '更新成功');
        } else {
            return redirect()->back()->withInput()->withErrors('提交失败');
        }
    }
}
