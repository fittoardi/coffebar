<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'category_id',
    'name',
    'slug',
    'description',
    'price',
    'image',
    'is_available',
    'sort_order',
])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    /**
     * The accessors to append to the model's array form.
     *
     * @var list<string>
     */
    protected $appends = [
        'image_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_available' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(ProductOption::class)->orderBy('sort_order');
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp '.number_format((float) $this->price, 0, ',', '.');
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return str_starts_with($this->image, 'http')
                ? $this->image
                : asset('storage/'.$this->image);
        }

        $name = strtolower($this->name);
        if (str_contains($name, 'latte') || str_contains($name, 'susu') || str_contains($name, 'cappuccino') || str_contains($name, 'tea') || str_contains($name, 'chocolate') || str_contains($name, 'cokelat') || str_contains($name, 'fusion')) {
            return asset('images/cup-latte.png');
        }

        if (str_contains($name, 'espresso') || str_contains($name, 'americano') || str_contains($name, 'cold brew') || str_contains($name, 'kopi') || str_contains($name, 'black')) {
            return asset('images/cup-black.png');
        }

        return asset('images/beans-cluster.png');
    }
}
