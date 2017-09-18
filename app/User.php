<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'name', 'email', 'password', 'nickname', 'active', 'dept_id', 'is_admin',
        //'tag', 'telephone', 'mobilephone', 'ip_address', 'last_login_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['deleted_at'];

    //多对多员工所属部门
//    public function depts()
//    {
//        return $this->belongsToMany('App\Models\Dept', 'userdepts')->withTimestamps();
//    }

    //多对一
    public function dept()
    {
        return $this->belongsTo('App\Models\Dept');
    }

    //个人收费标准
    public function prices()
    {
        return $this->hasMany('App\Models\PriceUser');
    }
}
