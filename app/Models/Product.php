<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
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

    // Remove or comment out the following line to stop appending image_urls:

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
}
