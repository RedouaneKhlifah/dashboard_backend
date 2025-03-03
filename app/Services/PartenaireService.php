<?php

namespace App\Services;

use App\Models\Partenaire;
use App\Repositories\PartenaireRepository;
use App\Repositories\TicketRepository;

class PartenaireService
{
    protected $partenaireRepository;
    protected $ticketRepository;

    public function __construct(PartenaireRepository $partenaireRepository , TicketRepository $ticketRepository)
    {
        $this->partenaireRepository = $partenaireRepository;
        $this->ticketRepository = app(TicketRepository::class);
    }

    public function getAllPartenaires($searchTerm = null, $perPage = 10)
    {
        return $this->partenaireRepository->getAllWithSearch($searchTerm, $perPage);
    }

    public function getPartenaire(Partenaire $partenaire)
    {
        return $this->partenaireRepository->find($partenaire);
    }

    public function createPartenaire(array $data)
    {
        return $this->partenaireRepository->create($data);
    }

    public function updatePartenaire(Partenaire $partenaire, array $data)
    {
        return $this->partenaireRepository->update($partenaire, $data);
    }

    public function deletePartenaire(Partenaire $partenaire)
    {
        return $this->partenaireRepository->delete($partenaire);
    }

    public function getPartenaireTicketsWithSum(array $data): array
    {
        $partenaire = Partenaire::findOrFail($data['partenaire_id']);

        $ticketData = $this->ticketRepository->getTicketsWithSum(
            $partenaire->id,
            $data['start_date'] ?? null,
            $data['end_date'] ?? null
        );

        return [
            'partenaire' => $partenaire,
            'tickets' => $ticketData['tickets'],
            'total_poids_net' => $ticketData['total_poids_net'],
            "product" => $ticketData['product']
        ];
    }
}