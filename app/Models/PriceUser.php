<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriceUser extends Model
{
    use SoftDeletes;

    protected $table = 'price_users';

    protected $fillable = ['begin_date', 'valid_date', 'user_id', 'price_id', 'breakfast' , 'lunch' , 'dinner'];

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
