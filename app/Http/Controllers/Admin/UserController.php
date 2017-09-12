<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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

    protected function validator(array $data)
    {
        return Validator::make($data ,[
            'username' => 'required|string|unique:users|max:255',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    public function store(Request $request)
    {
        $request->flash();
        $this->validator($request->input())->validate();
        dd($request->input());
    }
}
