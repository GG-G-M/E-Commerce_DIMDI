<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockChecker extends Model
{
    use HasFactory;

    // Table name (optional if follows convention)
    protected $table = 'stock_checkers';

    // Mass assignable attributes
    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'contact',
        'address',
    ];

    // Optional: Accessor for full name
    public function getFullNameAttribute()
    {
        return trim("{$this->firstname} {$this->middlename} {$this->lastname}");
    }
}
