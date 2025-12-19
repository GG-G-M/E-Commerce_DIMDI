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
        'is_archived',
    ];

    // Optional: Accessor for full name
    public function getFullNameAttribute()
    {
        return trim("{$this->firstname} {$this->middlename} {$this->lastname}");
    }

    /**
     * Archive this stock checker
     */
    public function archive()
    {
        $this->is_archived = true;
        return $this->save();
    }

    /**
     * Unarchive this stock checker
     */
    public function unarchive()
    {
        $this->is_archived = false;
        return $this->save();
    }

    /**
     * Scope a query to only include active stock checkers
     */
    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    /**
     * Scope a query to only include archived stock checkers
     */
    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }
}
