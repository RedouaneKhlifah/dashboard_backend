<?php

namespace App\Http\Controllers;

use App\Events\ModelUpdated;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(Request $request): JsonResponse
    {
        $searchTerm = $request->query('search');
        $perPage = $request->query('per_page', 10);
        $transactions = $this->transactionService->getAllTransactions($searchTerm, $perPage);
        return response()->json(['transactions' => $transactions , "balance" => Transaction::getBalance()] );
    }

    public function store(TransactionRequest $request): JsonResponse
    {
        $transaction = $this->transactionService->createTransaction($request->validated());
        $balance =  Transaction::getBalance();
        broadcast(new ModelUpdated($balance, 'total', 'updated'));
        broadcast(new ModelUpdated($transaction, 'transaction', 'created'));
        return response()->json(['transaction' => $transaction , "balance" => Transaction::getBalance()], 201);
    }

    public function show(Transaction $transaction): JsonResponse
    {
        return response()->json($transaction);
    }

    public function update(TransactionRequest $request, Transaction $transaction): JsonResponse
    {
        $transaction = $this->transactionService->updateTransaction($transaction, $request->validated());
        $balance =  Transaction::getBalance();
        broadcast(new ModelUpdated($balance, 'total', 'updated'));
        broadcast(new ModelUpdated($transaction, 'transaction', 'updated'));
        return response()->json(['transaction' => $transaction , "balance" => Transaction::getBalance()]  , 200 );
    }

    public function destroy(Transaction $transaction): JsonResponse
    {
        $this->transactionService->deleteTransaction($transaction);
        $balance = Transaction::getBalance();
        broadcast(new ModelUpdated($balance, 'total', 'updated'));
        broadcast(new ModelUpdated($transaction, 'transaction', 'deleted'));
        return response()->json(['transaction' => $transaction , "balance" => Transaction::getBalance()], status: 200);
    }
}
