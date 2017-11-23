<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\User;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('admin.role.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('admin.role.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $name = $request->input('name');
        $permissions = $request->input('permissions') ? $request->input('permissions') : [];
        //创建角色
        $role = Role::create(['name' => $name]);
        //对角色赋权
        $role->givePermissionTo($permissions);
        return redirect('admin/role');
    }

    public function edit(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        return view('admin.role.edit', compact('role','permissions'));
    }

    public function update(Request $request, $id)
    {
        $permissions = $request->input('permissions') ? $request->input('permissions') : [];
        $role = Role::findOrFail($id);
        $role->update(['name' => $request->input('name')]);
        //角色重新赋权
        $role->syncPermissions($permissions);
        return redirect()->back()->with('status', '处理完成。');
    }
}
