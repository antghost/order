<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserOrderStatus extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'breakfast', 'lunch', 'dinner'];

    protected $dates = ['deleted_at'];

    /**
     * 将三餐状态转为布尔值
     * @var array
     */
    protected $casts = [
        'breakfast' => 'boolean',
        'lunch' => 'boolean',
        'dinner' => 'boolean',
    ];
}
