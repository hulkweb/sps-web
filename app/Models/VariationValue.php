<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariationValue extends Model
{
    use HasFactory;

    public function variation_type(){
        return $this->belongsTo(VariationType::class,'variation_type_id');
    }
}
