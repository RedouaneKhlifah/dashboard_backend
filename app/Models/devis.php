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
        "client_id",
        'reference',
        'devis_date',
        'experation_date',
        'tva',
        'remise_type',
        'remise',
        'note',
    ];

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
                    ->withPivot('price_unitaire', 'quantity')
                    ->withTimestamps()->withTrashed();
    }
}

