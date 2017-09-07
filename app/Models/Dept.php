<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Dept extends Model
{
    protected $fillable = ['pid', 'name'];

    //多对多，部门内的所有人员
    public function users()
    {
        return $this->belongsToMany('App\User', 'userdepts');
    }
}
