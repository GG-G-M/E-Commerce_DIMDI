<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = ['name', 'is_archived'];

    /**
     * Archive the warehouse
     */
    public function archive()
    {
        $this->update(['is_archived' => true]);
    }

    /**
     * Unarchive the warehouse
     */
    public function unarchive()
    {
        $this->update(['is_archived' => false]);
    }

    /**
     * Scope to get only active warehouses
     */
    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    /**
     * Scope to get only archived warehouses
     */
    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }
}

