<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partenaire extends Model
{
    
    use HasFactory , SoftDeletes;

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
