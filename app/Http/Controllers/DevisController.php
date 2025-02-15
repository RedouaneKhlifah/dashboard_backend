<?php

namespace App\Http\Controllers;

use App\Events\ModelUpdated;
use App\Http\Requests\DevisRequest;
use App\Models\Devis;
use App\Services\DevisService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DevisController extends Controller
{
    protected $devisService;


    public function __construct(DevisService $devisService)
    {
        $this->devisService = $devisService;
    }

    public function index(Request $request): JsonResponse
    {
        $searchTerm = $request->query('search');
        $perPage = $request->query('per_page', 10);
        $devis = $this->devisService->getAllDevis($searchTerm, $perPage);
        return response()->json($devis);
    }

    public function store(DevisRequest $request): JsonResponse
    {
        $devis = $this->devisService->createDevis($request->validated());
        return response()->json($devis, 201);
    }

    public function show(Devis $devi): JsonResponse
    {
        $devis = $this->devisService->getDevis($devi);
        return response()->json($devis);
    }

    public function update(DevisRequest $request, Devis $devi): JsonResponse
    {
        $devis = $this->devisService->updateDevis($devi, $request->validated());
        broadcast(new ModelUpdated($devis, 'devis', 'updated'));
        return $devis
            ? response()->json($devis)
            : response()->json(['message' => 'Devis not found'], 404);
    }

    public function destroy(Devis $devi): JsonResponse
    {
        $success = $this->devisService->deleteDevis($devi);
        broadcast(new ModelUpdated($devi, 'devis', 'deleted'));
        return response()->json(null, 204);
    }
}