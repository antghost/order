<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeptController extends Controller
{
    public function index()
    {
        return view('admin.dept.index');
    }

    public function create()
    {
        return view('admin.dept.create');
    }
}
