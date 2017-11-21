<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function index()
    {
        return view('admin.role.index');
    }

    public function create()
    {
        return view('admin.role.create');
    }

    public function store(Request $request)
    {
        $name = $request->input('name');
        
    }
}
