<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportData extends Model
{
    protected $fillable = ['user_id', 'year', 'month', 'breakfasts', 'lunches', 'dinners',
        'breakfast_price', 'lunch_price', 'dinner_price', 'breakfast_amount', 'lunch_amount', 'dinner_amount'];
}
