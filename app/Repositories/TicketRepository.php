<?php

namespace App\Repositories;

use App\Models\Ticket;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TicketRepository
{
    protected $model;

    public function __construct(Ticket $model)
    {
        $this->model = $model;
    }

    public function getAllWithSearch($searchTerm = null, $perPage = 10)
    {
        $query = $this->model->with(['partenaire', 'product', 'client']);

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('partenaire', function ($q) use ($searchTerm) {
                    $q->where('matricule', 'like', "%{$searchTerm}%");
                })
                ->orWhereHas('product', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%");
                })
                ->orWhereHas('client', function ($q) use ($searchTerm) {
                    $q->where('first_name', 'like', "%{$searchTerm}%");
                    $q->orWhere('last_name', 'like', "%{$searchTerm}%");
                });
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function find(Ticket $ticket)
    {
        return $ticket->load(['partenaire', 'product', 'client']);
    }

    public function create(array $data)
    {
        $ticket = $this->model->create($data);
            // Load the relationships after the ticket is created
        return $ticket->load(['partenaire', 'product', 'client']);
    }

    public function update(Ticket $ticket, array $data)
    {
        // Update the ticket first
        $ticket->update($data);
    
        // Reload the relationships after the update
        return $ticket->load(['partenaire', 'product', 'client']);
    }

    public function delete(Ticket $ticket)
    {
        return $ticket->delete();
    }

    public function getTicketsWithSum(
        int $partenaireId, 
        ?string $startDate = null, 
        ?string $endDate = null
    ): array
    {
        // Convert dates to proper format with full-day coverage
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay()->toDateTimeString() : null;
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay()->toDateTimeString() : null;
    
        $baseQuery = Ticket::where('partenaire_id', $partenaireId)
            ->whereNull('deleted_at')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('created_at', [$startDate, $endDate]);
            })->with('product');
    
        // Clone the query before executing to get both results
        $totalQuery = clone $baseQuery;
    
        return [
            'tickets' => $baseQuery->orderBy('created_at', 'desc')->get(),
            'total_poids_net' => $totalQuery->sum(DB::raw('poids_brut - poids_tare')),
            "product" => $totalQuery->first()?->product
        ];
    }
}