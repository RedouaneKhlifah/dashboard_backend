<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory , SoftDeletes;
    protected $fillable = [
        'display_on_desktop',
        'name',
        'sku',
        'unit',
        'sale_price',
        'cost_price',
        'description',
        'tax',
        'stock',
        'reorder_point',
    ];

    protected $casts = [
        'display_on_desktop' => 'boolean',
        'sale_price'         => 'decimal:2',
        'cost_price'         => 'decimal:2',
        'tax'                => 'decimal:2',
        'stock'              => 'decimal:2',
        'reorder_point'      => 'decimal:2',
    ];

    protected $appends = ['image']; // Appending image attribute to model

    /**
     * Get all of the product's images.
     */
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    /**
     * Automatically create images for the product.
     *
     * @param array $imageUrls Array of image URLs to be saved.
     * @return void
     */
    public function createImages(array $imageUrls)
    {
        foreach ($imageUrls as $index => $url) {
            $this->images()->create([
                'url' => $url,
                'position' => $index + 1,
            ]);
        }
    }

    /**
     * Get the first image of the product.
     *
     * @return string|null
     */
    public function getImageAttribute()
    {
        return "https://images.lifesizecustomcutouts.com/image/cache/catalog/febProds21/SP000069-500x500.png";
    }

    public function getDescriptionAttribute($value)
    {
        return $value ?? '';
    }

    public function order()
    {
        return $this->belongsToMany(Order::class);
    }

    public function tickets()
    {
        return $this->belongsToMany(Ticket::class);
    }
}
