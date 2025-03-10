<?php

namespace App\Jobs;

use App\Enums\TicketEnums\StatusEnum;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateProductStock implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Ticket $ticket, public string $action = 'add')
    {
    }

    public function handle(): void
    {
        // Only process ENTRY tickets
        if ($this->ticket->status->value !== StatusEnum::ENTRY->value) {
            return;
        }

        // Calculate net weight
        $netWeight = $this->ticket->poids_brut - $this->ticket->poids_tare;

        // Update product stock based on the action
        if ($this->action === 'add') {
            $this->ticket->product()->increment('stock', $netWeight);
            
        } else {
            $this->ticket->product()->decrement('stock', $netWeight);
        }
    }
}