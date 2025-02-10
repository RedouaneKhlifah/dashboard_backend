<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partenaire extends Model
{
    use HasFactory;

    // Specify the fields that can be mass-assigned
    protected $fillable = [
        "name",
        "matricule",
    ];

    /**
     * Get the tickets associated with the partenaire.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'partenaire_id');
    }

}
