<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Price extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'breakfast' , 'lunch' , 'dinner' , 'begin_date' , 'status' ];

    protected $dates = ['deleted_at'];

    //属于该收费标准下的所有人员
    public function users()
    {
        return $this->hasMany('App\User');
    }
}
