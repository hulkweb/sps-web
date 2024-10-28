<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    public function state()
    {
        return $this->hasOne(State::class,'id','state');
    }


    public function city()
    {
        return $this->hasOne(City::class,'id','city');
    }
}
