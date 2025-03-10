<?php

namespace App\Observers;

use App\Jobs\UpdateProductStock;
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
            dispatch(new CreateOrderForTicketJob($ticket));
        }

        if ($ticket->status->value === 'ENTRY') {
            UpdateProductStock::dispatch($ticket, 'add');
        }
    }

    public function deleted(Ticket $ticket): void
    {
        // Dispatch the job to reverse the stock update for ENTRY tickets
        if ($ticket->status->value === 'ENTRY') {
            UpdateProductStock::dispatch($ticket, 'subtract');
        }
    }
}
