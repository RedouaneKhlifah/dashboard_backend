<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository
{
    protected $model;

    public function __construct(Transaction $model)
    {
        $this->model = $model;
    }

    public function getAllWithSearch($searchTerm = null, $perPage = 10)
    {
        $query = $this->model->newQuery();
    
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('reason', 'like', "%{$searchTerm}%");
            });
        }
    
        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
    
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(Transaction $transaction, array $data)
    {
        $transaction->update($data);
        return $transaction;
    }

    public function delete(Transaction $transaction)
    {
        return $transaction->delete();
    }
}
