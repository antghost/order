<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTime extends Model
{
    //
    protected $fillable = ['type', 'book_time', 'cancel_time'];
}
