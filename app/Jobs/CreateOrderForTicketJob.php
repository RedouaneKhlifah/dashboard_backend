<?php

namespace App\Jobs;

use App\Events\ModelUpdated;
use App\Models\Ticket;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\SharedService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class CreateOrderForTicketJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ticket;
    protected $sharedService;

    /**
     * Create a new job instance.
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
        $this->sharedService = new SharedService();
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // Create a order using data from the ticket.
        $order = Order::create([
            'ticket_id'       => $this->ticket->id,
            'client_id'       => $this->ticket->client_id,
            'reference'       =>  "DEVIS-" . now()->format('Y-m-d'),
            'order_date'      =>  $this->ticket->created_at,
            'expiration_date' => now()->addDays(30), 
            'tva'             => 0,
            'remise_type'     => 'PERCENT',  // or 'FIXED'
            'remise'          => 0,
            'note'            => '',
        ]);
        
            // Attach the product to the order.
            // Here, we're assuming:
            // - The product model has a 'price' attribute for the unit price.
            // - The ticket's 'number_prints' field represents the quantity.
            $order->products()->attach($this->ticket->product->id, [
                'price_unitaire' =>  0,
                'quantity'       => $this->ticket->poids_brut - $this->ticket->poids_tare,
                'ticket_id'      => $this->ticket->id

            ]);

            $order = $this->sharedService->formatProducts($order);

            broadcast(new ModelUpdated($order, 'order', 'deleted'));

    }
}
