<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory ,SoftDeletes;

    protected $table = 'orders';

    protected $fillable = [
        'ticket_id',
        'client_id',
        'reference',
        'bcn',
        'is_in_tone',
        'order_date',
        'expiration_date',
        'tva',
        'remise_type',
        'remise',
        'note',
    ];

    protected $appends = ['total']; // Adds total to the JSON output

    /**
     * Relationships
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class)->withTrashed();
    }

    public function client()
    {
        return $this->belongsTo(Client::class)->withTrashed();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product')
                    ->withPivot('price_unitaire', 'quantity' ,'ticket_id')
                    ->withTimestamps()
                    ->withTrashed();
    }

    public function facture()
    {
        return $this->hasOne(Facture::class);
    }

    /**
     * Calculate total attribute
     */
    public function getTotalAttribute()
    {
        // Ensure products is a collection
        $products = collect($this->products);
    
        // Calculate subtotal (HT) - sum of (unit price * quantity)
        $subtotal = $products->sum(function ($product) {
            return data_get($product, 'sale_price', 0) * data_get($product, 'quantity', 0);
        });
    
        // Calculate TVA amount (TVA% * subtotal)
        $tvaAmount = ($subtotal * $this->tva) / 100;
    
        // Calculate Total TTC before applying the discount
        $totalTTC = $subtotal + $tvaAmount;
    
        // Calculate Remise amount based on type (PERCENT or FIXED)
        $remiseAmount = $this->remise_type === "PERCENT"
            ? ($subtotal * $this->remise) / 100  // Discount applied on subtotal (HT)
            : $this->remise;
    
        // Final total after applying the remise
        return round($totalTTC - $remiseAmount, 2);
    }
    
}
