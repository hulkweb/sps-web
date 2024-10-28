<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }


    public function payment() {
        return $this->hasMany(Payment::class);
    }

    public function assign() {
        return $this->hasOne(AssignOrder::class,'order_id','id')->latest();
    }

    public function address()
    {
        return $this->hasOne(UserAddress::class,'id','shipping_address');
    }


}
