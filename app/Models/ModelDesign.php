<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelDesign extends Model
{
    use HasFactory;

    protected $fillable = [
        'industry_id',
        'category_id',
        'product_type_id',
        'shoot_type_id',
        'image',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    public function shootType()
    {
        return $this->belongsTo(ShootType::class);
    }

    /**
     * Get the full image URL
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        return asset($this->image);
    }

    /**
     * Get the full image path
     */
    public function getImagePathAttribute()
    {
        if (!$this->image) {
            return null;
        }

        return public_path($this->image);
    }
}
