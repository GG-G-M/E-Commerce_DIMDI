<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected $fillable = [
        'title',
        'description_1',
        'description_2',
        'feature_1_title',
        'feature_1_description',
        'feature_2_title',
        'feature_2_description',
        'image',
        'is_archived',
    ];

    protected $casts = [
        'is_archived' => 'boolean',
    ];

    /**
     * Archive this About entry
     */
    public function archive()
    {
        return $this->update(['is_archived' => true]);
    }

    /**
     * Unarchive this About entry
     */
    public function unarchive()
    {
        return $this->update(['is_archived' => false]);
    }
}
