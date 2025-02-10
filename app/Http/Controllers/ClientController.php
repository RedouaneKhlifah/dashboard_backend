<?php

namespace App\Http\Controllers;

use App\Events\ModelUpdated;
use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Services\ClientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;


class ClientController extends Controller
{
    protected $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    public function index(Request $request): JsonResponse
    {
        $searchTerm = $request->query('search');
        $perPage = $request->query('per_page', 10);
        $clients = $this->clientService->getAllClients($searchTerm, $perPage);
        return response()->json($clients);
    }

    public function store(ClientRequest $request): JsonResponse
    {
        $client = $this->clientService->createClient($request->validated());
        broadcast(new ModelUpdated($client, 'client', 'created'));
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
        broadcast(new ModelUpdated($client, 'client', 'updated'));

        return $client
            ? response()->json($client)
            : response()->json(['message' => 'Client not found'], 404);
    }

    public function destroy(Client $client): JsonResponse
    {
        $success = $this->clientService->deleteClient($client);
        broadcast(new ModelUpdated($client, 'client', 'deleted'));

        return response()->json(null, 204);
    }
}