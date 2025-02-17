<?php

namespace App\Services;

use App\Models\Partenaire;
use App\Models\Ticket;
use App\Repositories\TicketRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class TicketService
{
    protected $repository;

    public function __construct(TicketRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllTickets($searchTerm = null, $perPage = 10): LengthAwarePaginator
    {
        return $this->repository->getAllWithSearch($searchTerm, $perPage);
    }

    public function getTicket(Ticket $ticket): Ticket
    {
        return $this->repository->find($ticket);
    }

    public function createTicket(array $data): Ticket
    {
        
        return $this->repository->create($data);
    }

    public function updateTicket(Ticket $ticket, array $data)
    {
        return $this->repository->update($ticket, $data);
    }

    public function deleteTicket(Ticket $ticket): bool | null
    {
        return $this->repository->delete($ticket);
    }
}