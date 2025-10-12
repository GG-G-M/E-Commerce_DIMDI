<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'brand',
        'is_active',
    ];

    // A product belongs to a category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // A product has many variants
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function scopeFilterByCategory($query, $categoryId)
{
    return $query->where('category_id', $categoryId);
}

public function scopeFilterByStatus($query, $status)
{
    switch ($status) {
        case 'active':
            return $query->where('is_active', true)->where('is_archived', false);
        case 'inactive':
            return $query->where('is_active', false)->where('is_archived', false);
        case 'archived':
            return $query->where('is_archived', true);
        default:
            return $query;
    }
}

}
