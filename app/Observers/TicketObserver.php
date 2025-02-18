<?php

namespace App\Observers;

use App\Models\Ticket;
use App\Jobs\CreateOrderForTicketJob;
use Illuminate\Support\Facades\Log;

class TicketObserver
{
    /**
     * Handle the Ticket "created" event.
     */
    public function created(Ticket $ticket)
    {
        if ($ticket->status->value === 'EXIT') {
            // Create a order for the ticket
            dispatch(new CreateOrderForTicketJob($ticket));
        }
    }
}
