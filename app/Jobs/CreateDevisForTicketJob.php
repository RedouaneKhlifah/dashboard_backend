<?php

namespace App\Jobs;

use App\Models\Ticket;
use App\Models\Devis;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class CreateDevisForTicketJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ticket;

    /**
     * Create a new job instance.
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // Create a devis using data from the ticket.
        $devis = Devis::create([
            'ticket_id'       => $this->ticket->id,
            'client_id'       => $this->ticket->client_id,
            'reference'       =>  "DEVIS-" . now()->format('Y-m-d'),
            'devis_date'      =>  $this->ticket->created_at,
            'expiration_date' => now()->addDays(30), 
            'tva'             => 0,
            'remise_type'     => 'PERCENT',  // or 'FIXED'
            'remise'          => 0,
            'note'            => '',
        ]);
        
            // Attach the product to the devis.
            // Here, we're assuming:
            // - The product model has a 'price' attribute for the unit price.
            // - The ticket's 'number_prints' field represents the quantity.
            $devis->products()->attach($this->ticket->product->id, [
                'price_unitaire' =>  0,
                'quantity'       => $this->ticket->poids_brut - $this->ticket->poids_tare,
                'ticket_id'      => $this->ticket->id

            ]);
    }
}
