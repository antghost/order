<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return view('admin.permission.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permission.create');
    }

    public function store(Request $request)
    {
        $name = $request->input('name');
        Permission::create(['name' => $name]);
//        $user = User::find(32);
//        $user->syncPermissions(['admin', 'manger']);
        return redirect('admin/permit');
    }

}
