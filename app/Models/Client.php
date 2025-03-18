<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory , SoftDeletes;

    // Specify the fields that can be mass-assigned
    protected $fillable = [
        "company",
        'ice',
        'email',
        'phone',
        'country',
        'city',
        'address',
    ];

    /**
     * Get the tickets associated with the client.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'client_id');
    }
}
