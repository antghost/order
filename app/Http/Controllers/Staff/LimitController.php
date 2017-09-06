<?php

namespace App\Http\Controllers\staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LimitController extends Controller
{
    public function index()
    {
        return view('staff.limit.index');
    }
}
