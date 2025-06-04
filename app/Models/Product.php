<?php

namespace App\Models;

use App\Products\Types\ProductTypeInterface;
use App\Products\Types\ProductTypeRegistry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * @property-read \App\Models\ProductCategory|null $category
 */
class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_category_id',
        'name',
        'short_name',
        'type',
        'price',
        'allow_custom_price',
        'is_active',
        'metadata',
        'description',
        'cover_image',
    ];

    protected $casts = [
        'price' => 'integer',
        'allow_custom_price' => 'boolean',
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    protected $appends = [
        'price_dollars',
        'category_name',
        'display_name',
        'cover_image_url',
        'checkout_form_schema',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function typeInstance(): ?ProductTypeInterface
    {
        return ProductTypeRegistry::getByIdentifier($this->type);
    }

    public function isCustomAmount(): bool
    {
        return $this->type === 'donation_custom';
    }

    public function getCheckoutFormSchemaAttribute(): array
    {
        return $this->typeInstance()?->checkoutFormSchema() ?? [];
    }

    public function getCategoryNameAttribute(): ?string
    {
        return $this->category?->name;
    }

    public function getDisplayNameAttribute(): string
    {
        if ($this->category_name) {
            return "$this->category_name ($this->name)";
        }

        return $this->name;
    }

    public function getPriceDollarsAttribute(): string
    {
        return number_format($this->price / 100, 2, '.', '');
    }

    public function getCoverImageUrlAttribute()
    {
        if (! $this->cover_image) {
            return null;
        }

        return Storage::disk('s3')->url($this->cover_image);
    }
}
