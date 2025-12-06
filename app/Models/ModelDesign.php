<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelDesign extends Model
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
        
        $modelDesignName = str_replace(['/', '\\', ' ', '?', '*', '|', '<', '>', ':', '"'], '_', $this->name);
        return asset('upload/Model Design/' . $modelDesignName . '/' . $this->image);
    }

    /**
     * Get the full image path
     */
    public function getImagePathAttribute()
    {
        if (!$this->image) {
            return null;
        }
        
        $modelDesignName = str_replace(['/', '\\', ' ', '?', '*', '|', '<', '>', ':', '"'], '_', $this->name);
        return public_path('upload/Model Design/' . $modelDesignName . '/' . $this->image);
    }
}
