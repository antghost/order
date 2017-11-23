<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\User;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->delete();
        DB::table('permissions')->delete();
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');
        //
        Permission::create(['name' => '系统管理员']);
        Permission::create(['name' => '餐厅管理员']);
        $role = Role::create(['name' => '系统管理员']);
        $role->givePermissionTo('系统管理员');
        $user = User::find('32');
        $user->syncRoles('系统管理员');

    }
}
