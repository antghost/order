<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\BookBreakfast;
use App\Models\CancelBreakfast;

class BreakfastController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('user.breakfast.index');
    }

    public function create()
    {
        $id = Auth::user()->id;
        $bookBreakfast = BookBreakfast::find($id);
        $cancelBreakfast = CancelBreakfast::find($id);
        return view('user.breakfast.create',[
            'bookBreakfast' => $bookBreakfast,
            'cancelBreakfast' => $cancelBreakfast,
        ]);
    }
}
