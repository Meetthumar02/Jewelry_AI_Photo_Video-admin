<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'industry_id',
        'name',
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

    public function productTypes()
    {
        return $this->hasMany(ProductType::class);
    }
}
