<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    // Specify the fields that can be mass-assigned
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'country',
        'city',
        'address',
    ];

    // Ensure full_name is always included when retrieving the model
    protected $appends = ['full_name'];

    // Accessor to get full name
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the tickets associated with the client.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'client_id');
    }
}
