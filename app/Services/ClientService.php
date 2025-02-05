<?php

namespace App\Services;

use App\Models\Client;
use App\Repositories\ClientRepository;

class ClientService
{
    protected $repository;

    public function __construct(ClientRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllClients()
    {
        return $this->repository->all();
    }

    public function getClient(Client $client) 
    {
        return $this->repository->find($client);
    }

    public function createClient(array $data)
    {
        return $this->repository->create($data);
    }

    public function updateClient(Client $client, array $data)
    {
        return $this->repository->update($client, $data);
    }

    public function deleteClient(Client $client)
    {
        return $this->repository->delete($client);
    }
}