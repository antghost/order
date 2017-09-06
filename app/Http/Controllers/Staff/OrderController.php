<?php

namespace App\Http\Controllers\staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index()
    {
        return view('staff.order.index');
    }
}
