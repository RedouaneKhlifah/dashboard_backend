<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Transaction extends Model
{
    use HasFactory ;

    const TYPE_CREDIT = "CREDIT";
    const TYPE_DEBIT = "DEBIT";
    // Specify the fields that can be mass-assigned
    protected $fillable = [
        'reason',
        'amount',
        'type',
        'note',
    ];

       // Automatically clear the cache when a transaction is saved or deleted
       protected static function booted()
       {
           // Clear the cache after saving or updating a transaction
           static::saved(function () {
               Cache::forget('total_balance');
           });

           static::updated(function () {
               Cache::forget('total_balance');
           });
   
           // Clear the cache after deleting a transaction
           static::deleted(function () {
               Cache::forget('total_balance');
           });
       }
   

    public static function getBalance()
    {
        // Try to get the balance from the cache
        return cache()->remember('total_balance', 60, function () {
            // Sum of all debit transactions
            $debitTotal = self::where('type', self::TYPE_DEBIT)->sum('amount');
            
            // Sum of all credit transactions
            $creditTotal = self::where('type', self::TYPE_CREDIT)->sum('amount');
            
            // Return the difference between debits and credits (balance)
            return $creditTotal - $debitTotal;
        });
    }
    
}
