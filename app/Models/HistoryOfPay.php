<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryOfPay extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'total_ton', 'price_per_ton', 'total_gain', 'start_date', 'end_date'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    
}

