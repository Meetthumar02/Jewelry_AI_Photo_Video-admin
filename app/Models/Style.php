<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Style extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Get the full image URL
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }
        
        $styleName = str_replace(['/', '\\', ' ', '?', '*', '|', '<', '>', ':', '"'], '_', $this->name);
        return asset('upload/Style/' . $styleName . '/' . $this->image);
    }

    /**
     * Get the full image path
     */
    public function getImagePathAttribute()
    {
        if (!$this->image) {
            return null;
        }
        
        $styleName = str_replace(['/', '\\', ' ', '?', '*', '|', '<', '>', ':', '"'], '_', $this->name);
        return public_path('upload/Style/' . $styleName . '/' . $this->image);
    }
}
