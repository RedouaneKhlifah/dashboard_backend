<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'ticket_id',
        'reference',
        'experation_date',
        'tva',
        'remise',
        'note',
    ];

    // Relationships
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'devis_product')
                    ->withPivot('price_unitaire', 'quantity', 'unit')
                    ->withTimestamps();
    }
}

