<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        "matricule",
        'first_name',
        'last_name',
        'national_id',
        'address',
        'city',
        'date_of_engagement',
        "cnss_number", // optional
        "birth_date", // optional
    ];

    // Ensure full_name is always included when retrieving the model
    protected $appends = ['full_name'];

    // Accessor to get full name
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function paymentHistories()
    {
        return $this->hasMany(HistoryOfPay::class);
    }

}
