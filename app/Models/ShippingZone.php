<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'pivot_point_id',
        'zone_name',
        'min_distance',
        'max_distance',
        'shipping_fee',
        'description',
        'is_active'
    ];

    protected $casts = [
        'min_distance' => 'decimal:2',
        'max_distance' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function pivotPoint()
    {
        return $this->belongsTo(ShippingPivotPoint::class, 'pivot_point_id');
    }
}
