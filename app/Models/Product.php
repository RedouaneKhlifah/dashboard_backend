<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * - display_on_desktop: boolean flag for visibility on the desktop application.
     * - name: Product name.
     * - sku: Optional product SKU.
     * - unit: Unit of measurement (e.g., 'kg', 'pcs', etc.).
     * - sale_price: Selling price.
     * - cost_price: Optional cost price.
     * - description: Optional description.
     * - tax: Optional tax amount or percentage.
     * - image: Optional image path/URL.
     * - stock: Current inventory quantity.
     * - reorder_point: Inventory level at which a reorder alert is triggered.
     */
    protected $fillable = [
        'display_on_desktop',
        'name',
        'sku',
        'unit',
        'sale_price',
        'cost_price',
        'description',
        'tax',
        'image',
        'stock',
        'reorder_point',
    ];

    
    /**
     * Append the image_url attribute to the model's array/json output.
     */
    protected $appends = ['image_url'];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'display_on_desktop' => 'boolean',
        'sale_price'         => 'decimal:2',
        'cost_price'         => 'decimal:2',
        'tax'                => 'decimal:2',
        'stock'              => 'decimal:2',
        'reorder_point'      => 'decimal:2',
    ];
    

    /**
     * The "booted" method of the model.
     *
     * This method automatically sets the unit to 'kg' if the product is sold by weight.
     */
    protected static function booted()
    {
        static::saving(function ($product) {
            if ($product->display_on_desktop) {
                $product->unit = 'kg';
            }
        });
    }

       /**
     * Get the full URL for the image.
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

}
