<?php

namespace App\Observers;

use App\Models\Ticket;
use App\Jobs\CreateDevisForTicketJob;
use Illuminate\Support\Facades\Log;

class TicketObserver
{
    /**
     * Handle the Ticket "created" event.
     */
    public function created(Ticket $ticket)
    {
        if ($ticket->status->value === 'EXIT') {
            // Create a devis for the ticket
            dispatch(new CreateDevisForTicketJob($ticket));
        }
    }
}
