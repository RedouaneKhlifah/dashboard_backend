<?php

namespace App\Http\Controllers;

use App\Events\ModelUpdated;
use App\Http\Requests\PartenaireRequest;
use App\Models\Partenaire;
use App\Services\PartenaireService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class PartenaireController extends Controller
{
    protected $partenaireService;

    public function __construct(PartenaireService $partenaireService)
    {
        $this->partenaireService = $partenaireService;
    }

    public function index(Request $request): JsonResponse
    {
        $searchTerm = $request->query('search');
        $perPage = $request->query('per_page', 10);
        $partenaires = $this->partenaireService->getAllPartenaires($searchTerm, $perPage);;
        return response()->json($partenaires);
    }

    public function store(PartenaireRequest $request): JsonResponse
    {
        $partenaire = $this->partenaireService->createPartenaire($request->validated());
        broadcast(new ModelUpdated($partenaire, 'partenaire', 'created'));
        return response()->json($partenaire, 201);
    }

    public function show(Partenaire $partenaire): JsonResponse
    {
        $partenaire = $this->partenaireService->getPartenaire($partenaire);
        return response()->json($partenaire);
    }

    public function update(PartenaireRequest $request, Partenaire $partenaire): JsonResponse
    {
        $partenaire = $this->partenaireService->updatePartenaire($partenaire, $request->validated());
        broadcast(new ModelUpdated($partenaire, 'partenaire', 'updated'));
        return response()->json($partenaire);
    }

    public function destroy(Partenaire $partenaire): JsonResponse
    {
        $this->partenaireService->deletePartenaire($partenaire);
        broadcast(new ModelUpdated($partenaire, 'partenaire', 'deleted'));
        return response()->json(null, 204);
    }
}