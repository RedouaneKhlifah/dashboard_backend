<?php

namespace App\Repositories;

use App\Models\Ticket;

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
}