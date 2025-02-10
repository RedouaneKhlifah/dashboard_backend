<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use App\Enums\TicketEnums\StatusEnum;

class Ticket extends Model
{
    use HasFactory;

    // Define the enum values for status
    public const STATUS_ENTRY = 'ENTRY';
    public const STATUS_EXIT = 'EXIT';

    // Specify the fields that can be mass-assigned
    protected $fillable = [
        'partenaire_id',
        'product_id',
        'client_id',
        'number_prints',
        'poids_brut',
        'poids_tare',
        'status', 
    ];

    // Cast the status attribute to an enum
    protected $casts = [
        'status' => StatusEnum::class,
    ];

    /**
     * Get the partenaire associated with the ticket.
     */
    public function partenaire()
    {
        return $this->belongsTo(Partenaire::class, 'partenaire_id');
    }

    /**
     * Get the product associated with the ticket.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get the client associated with the ticket.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Boot the model to add custom validation logic.
     */
    protected static function boot()
    {
        parent::boot();

        // Add a saving event to validate the model before saving
        static::saving(function ($ticket) {
            // If the status is EXIT, ensure client_id is set
            if ($ticket->status === self::STATUS_EXIT && !$ticket->client_id) {
                $validator = Validator::make(
                    ['client_id' => $ticket->client_id],
                    ['client_id' => 'required|exists:clients,id']
                );

                if ($validator->fails()) {
                    throw new \Illuminate\Validation\ValidationException($validator);
                }
            }
        });
    }
}
