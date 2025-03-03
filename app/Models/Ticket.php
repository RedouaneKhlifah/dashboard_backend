<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use App\Enums\TicketEnums\StatusEnum;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory , SoftDeletes;

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

    // append poids_net attribute
    protected $appends = ['poids_net'];

    // Define the poids_net attribute
    public function getPoidsNetAttribute()
    {
        return $this->poids_brut - $this->poids_tare;
    }

    /**
     * Get the partenaire associated with the ticket.
     */
    public function partenaire()
    {
        return $this->belongsTo(Partenaire::class, 'partenaire_id')->withTrashed();
    }

    /**
     * Get the product associated with the ticket.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }

    /**
     * Get the client associated with the ticket.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id')->withTrashed();
    }

    /**
     * Boot the model to add validation and prevent updates in a single request.
     */
    protected static function boot()
    {
        parent::boot();

        // Validate and prevent modifications in a single request
        static::saving(function ($ticket) {
            // If updating, prevent modification of protected fields
            if ($ticket->exists) {
                foreach (['client_id', 'partenaire_id', 'product_id', 'status'] as $field) {
                    if ($ticket->isDirty($field)) {
                        $ticket->$field = $ticket->getOriginal($field);
                    }
                }
            }

            // If status is EXIT, ensure client_id is set
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
