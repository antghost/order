<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FeeController extends Controller
{
    public function index()
    {
        return view('admin.fee.index');
    }

    public function create()
    {
        return view('admin.fee.create');
    }
}
