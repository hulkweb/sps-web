<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function user()
    {
        return $this->belongsTo(User::class, 'provider_reference_id','id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id','id');
    }
}
