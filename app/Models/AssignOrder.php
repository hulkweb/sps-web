<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignOrder extends Model
{
    use HasFactory;

    public function order() {
        return $this->hasMany(Order::class,'id','order_id');
    }

    public function user()
    {
        return $this->hasOne(User::class,'id','driver_id');
    }

}
