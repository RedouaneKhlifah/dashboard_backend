<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    use HasFactory;

    protected $table = 'devis';

    protected $fillable = [
        'ticket_id',
        'client_id',
        'reference',
        'devis_date',
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
        return $this->belongsToMany(Product::class, 'devis_product')
                    ->withPivot('price_unitaire', 'quantity' ,'ticket_id')
                    ->withTimestamps()
                    ->withTrashed();
    }

    /**
     * Calculate total attribute
     */
    public function getTotalAttribute()
    {
        // Ensure products is a collection
        $products = collect($this->products);

        // Calculate subtotal (sum of product price * quantity)
        $subtotal = $products->sum(function ($product) {
            return data_get($product, 'pivot.price_unitaire', 0) * data_get($product, 'pivot.quantity', 0);
        });

        // Calculate TVA amount
        $tvaAmount = ($subtotal * $this->tva) / 100;

        // Calculate Remise amount based on type (PERCENT or FIXED)
        $remiseAmount = $this->remise_type === "PERCENT"
            ? ($subtotal * $this->remise) / 100
            : $this->remise;

        // Final total calculation
        return round($subtotal + $tvaAmount - $remiseAmount, 2);
    }
}
