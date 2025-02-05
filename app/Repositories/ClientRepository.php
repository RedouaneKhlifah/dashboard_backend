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

    public function all()
    {
        return $this->model->all();
    }

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