<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Dept;

class DeptController extends Controller
{
    public function index()
    {
        return view('admin.dept.index', ['depts' => Dept::all()]);
    }

    public function create()
    {
        return view('admin.dept.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:depts|max:255'
        ]);

        $dept = new Dept();
        $dept->name = $request->input('name');

        if ($dept->save()){
            return redirect('admin/dept');
        } else {
            return redirect()->back()->withErrors('添加失败');
        }
    }
}
