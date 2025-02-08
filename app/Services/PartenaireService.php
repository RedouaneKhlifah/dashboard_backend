<?php

namespace App\Services;

use App\Models\Partenaire;
use App\Repositories\PartenaireRepository;

class PartenaireService
{
    protected $repository;

    public function __construct(PartenaireRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllPartenaires($searchTerm = null, $perPage = 10)
    {
        return $this->repository->getAllWithSearch($searchTerm, $perPage);
    }

    public function getPartenaire(Partenaire $partenaire)
    {
        return $this->repository->find($partenaire);
    }

    public function createPartenaire(array $data)
    {
        return $this->repository->create($data);
    }

    public function updatePartenaire(Partenaire $partenaire, array $data)
    {
        return $this->repository->update($partenaire, $data);
    }

    public function deletePartenaire(Partenaire $partenaire)
    {
        return $this->repository->delete($partenaire);
    }
}