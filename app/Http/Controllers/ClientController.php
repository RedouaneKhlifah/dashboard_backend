<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Services\ClientService;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{
    protected $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    public function index(): JsonResponse
    {
        $clients = $this->clientService->getAllClients();
        return response()->json($clients);
    }

    public function store(ClientRequest $request): JsonResponse
    {
        $client = $this->clientService->createClient($request->validated());
        return response()->json($client, 201);
    }

    public function show(Client $client): JsonResponse
    {
        $client = $this->clientService->getClient($client);
        return response()->json($client);
    }

    public function update(ClientRequest $request, Client $client): JsonResponse
    {
        $client = $this->clientService->updateClient($client, $request->validated());
        return $client
            ? response()->json($client)
            : response()->json(['message' => 'Client not found'], 404);
    }

    public function destroy(Client $client): JsonResponse
    {
        $success = $this->clientService->deleteClient($client);
        return $success
            ? response()->json(null, 204)
            : response()->json(['message' => 'Client not found'], 404);
    }
}