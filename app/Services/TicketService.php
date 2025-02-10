<?php

namespace App\Services;

use App\Models\Partenaire;
use App\Models\Ticket;
use App\Repositories\TicketRepository;

class TicketService
{
    protected $repository;

    public function __construct(TicketRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllTickets($searchTerm = null, $perPage = 10)
    {
        return $this->repository->getAllWithSearch($searchTerm, $perPage);
    }

    public function getTicket(Ticket $ticket)
    {
        return $this->repository->find($ticket);
    }

    public function createTicket(array $data)
    {
        $matriculeRecord = Partenaire::firstOrCreate(
            ['matricule' => $data['matricule']]
        );

        $data['partenaire_id'] = $matriculeRecord->id;
        
        return $this->repository->create($data);
    }

    public function updateTicket(Ticket $ticket, array $data)
    {
        return $this->repository->update($ticket, $data);
    }

    public function deleteTicket(Ticket $ticket)
    {
        return $this->repository->delete($ticket);
    }
}