<?php


namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    function roleData(){
        return $this->hasOne(Role::class,'id','role_id');
    }

    function customerData(){
        return $this->hasMany(User::class,'createBy','id');
    }


    function sellerOrder(){
        return $this->hasMany(Order::class,'createBy','id');
    }


    function order(){
        return $this->hasMany(Order::class,'user_id','id');
    }


    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }
}
