<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    protected $fillable = ['product_id', 'variation_name', 'variation_price'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
