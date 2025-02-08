<?php

namespace App\Repositories;

use App\Models\Client;

class ClientRepository
{
    protected $model;

    public function __construct(Client $model)
    {
        $this->model = $model;
    }

    public function getAllWithSearch($searchTerm = null, $perPage = 10)
    {
        $query = $this->model->newQuery();
    
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('last_name', 'like', "%{$searchTerm}%");
            });
        }
    
        return $query->orderBy('created_at', 'desc')->paginate($perPage);    }

    public function find(Client $client)
    {
        return $client ;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(Client $client, array $data)
    {
        $client->update($data);
        return $client;
    }

    public function delete(Client $client)
    {
        return $client->delete();
    }

}