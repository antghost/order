<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    protected $fillable = ['name', 'type', 'begin_date', 'end_date', 'user_id'];

    public function getTypeAttribute($value)
    {
        ($value == 0) ? $chn = '假期' : $chn = '补班';
        return $chn;
    }
}
