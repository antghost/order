<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use App\User;

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
//        $role = Role::create(['name' => $name]);
//        $user = User::find(32);
//        $user->syncRoles($name);
        $role = Role::findByName('role-admin');
        $role->syncPermissions(['admin','manger','管理员']);
        return redirect('admin/role');
    }
}
