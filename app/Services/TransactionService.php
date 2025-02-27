<?php

namespace App\Services;

use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class TransactionService
{
    protected $repository;

    public function __construct(TransactionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllTransactions($searchTerm = null, $perPage = 10): LengthAwarePaginator
    {
        return $this->repository->getAllWithSearch($searchTerm, $perPage);
    }

    public function createTransaction(array $data)
    {
        return $this->repository->create($data);
    }

    public function updateTransaction(Transaction $transaction, array $data)
    {
        return $this->repository->update($transaction, $data);
    }

    public function deleteTransaction(Transaction $transaction)
    {
        return $this->repository->delete($transaction);
    }
}
