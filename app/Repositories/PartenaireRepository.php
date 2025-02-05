<?php

namespace App\Repositories;

use App\Models\Partenaire;

class PartenaireRepository
{
    protected $model;

    public function __construct(Partenaire $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find(Partenaire $partenaire)
    {
        return $partenaire;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(Partenaire $partenaire, array $data)
    {
        $partenaire->update($data);
        return $partenaire;
    }

    public function delete(Partenaire $partenaire)
    {
        return $partenaire->delete();
    }
}