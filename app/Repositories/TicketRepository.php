<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Ticket;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
                    $q->where('company_ice', 'like', "%{$searchTerm}%");

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
            ->where('status', Ticket::STATUS_ENTRY)
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

    public function getTotalProductStock($startDate, $endDate)
    {
        return Product::whereBetween('created_at', [$startDate, $endDate])
                ->sum('stock');
    }
    public function getTopPartenairesByNetWeight($startDate, $endDate): array
    {
        $results = Ticket::with(['partenaire' => fn($q) => $q->withTrashed()])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('partenaire_id, SUM(poids_brut - poids_tare) as total_net')
            ->groupBy('partenaire_id')
            ->orderByDesc('total_net')
            ->limit(10)
            ->get();

        return $results->map(function ($item) {
            if (!$item->partenaire) return null;
            
            return [
                'matricule' => $item->partenaire->matricule,
                'name' => $item->partenaire->name,
                'total_net' => $item->total_net
            ];
        })->filter()->values()->toArray();
    }
}