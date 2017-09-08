<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Dept;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.user.index', ['users' => User::paginate(15), 'depts' => Dept::all()]);
    }

    public function create()
    {
        return view('admin.user.create', ['depts' => Dept::all()]);
    }

    public function store(Request $request)
    {
        dd($request->input());
    }
}
