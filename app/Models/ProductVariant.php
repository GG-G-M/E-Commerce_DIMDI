<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'sku', 'price', 'stock', 'image'];

    // A variant belongs to a product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Many-to-Many relationship with attribute values
    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'variant_attributes', 'variant_id', 'value_id');
    }
}
