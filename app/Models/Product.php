<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function type()
    {
        return $this->belongsTo(VariationType::class);
    }
    public function value()
    {
        return $this->belongsTo(VariationValue::class);
    }
    public function variations()
    {
        return $this->hasMany(VariationProduct::class);
    }



}
