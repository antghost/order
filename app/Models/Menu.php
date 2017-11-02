<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['name', 'type', 'active'];
    public function getTypeAttribute($value)
    {
        switch ($value){
            case 1 :
                $chn = '早餐';
                break;
            case 2 :
                $chn = '午餐';
                break;
            case 3 :
                $chn = '晚餐';
                break;
            case 4 :
                $chn = '下午茶';
                break;
            default:
                $chn = '未知';
        }

        return $chn;
    }
}
