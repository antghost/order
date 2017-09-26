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
        'username', 'name', 'email', 'password', 'nickname', 'active', 'dept_id', 'price_id', 'is_admin',
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

    //所属部门
    public function dept()
    {
        return $this->belongsTo('App\Models\Dept');
    }

    //收费标准
    public function price()
    {
        return $this->belongsTo('App\Models\Price');
    }

    //收费标准个人历史
    public function priceUsers()
    {
        return $this->hasMany('App\Models\PriceUser');
    }

    /**
     * 用户用餐状态
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userOrderStatuses()
    {
        return $this->hasOne('App\Models\UserOrderStatus');
    }

    /**
     * 用户早餐开餐记录
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookBreakfast()
    {
        return $this->hasMany('App\Models\BookBreakfast');
    }

    /**
     * 用户午餐开餐记录
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookLunch()
    {
        return $this->hasMany('App\Models\BookLunch');
    }

    /**
     * 用户晚餐开餐记录
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookDinner()
    {
        return $this->hasMany('App\Models\BookDinner');
    }

    /**
     * 用户早餐停餐记录
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cancelBreakfast()
    {
        return $this->hasMany('App\Models\CancelBreakfast');
    }

    /**
     * 用户午餐停餐记录
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cancelLunch()
    {
        return $this->hasMany('App\Models\CancelLunch');
    }

    /**
     * 用户晚餐停餐记录
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function CancelDinner()
    {
        return $this->hasMany('App\Models\cancelDinner');
    }
}
