<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Userdept extends Model
{
    protected $fillable = ['dept_id', 'user_id'];

    protected $primaryKey = ['dept_id', 'user_id'];

    public $incrementing = false;

}
