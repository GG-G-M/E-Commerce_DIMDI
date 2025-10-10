<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    use HasFactory;

    protected $fillable = ['attribute_id', 'value'];

    // An attribute value belongs to an attribute
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    // Many-to-Many relationship with product variants
    public function variants()
    {
        return $this->belongsToMany(ProductVariant::class, 'variant_attributes', 'value_id', 'variant_id');
    }
}
