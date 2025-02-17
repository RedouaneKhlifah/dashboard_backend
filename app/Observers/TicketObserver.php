<?php

namespace App\Observers;

use App\Models\Ticket;
use App\Jobs\CreateDevisForTicketJob;

class TicketObserver
{
    /**
     * Handle the Ticket "created" event.
     */
    public function created(Ticket $ticket)
    {
        dispatch(new CreateDevisForTicketJob($ticket));
    }
}
