<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'cover_image'];

    protected $appends = ['cover_image_url'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function getCoverImageUrlAttribute()
    {
        if (! $this->cover_image) {
            return null;
        }

        return asset("storage/{$this->cover_image}");
    }
}
