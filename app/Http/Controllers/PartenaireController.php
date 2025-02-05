<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartenaireRequest;
use App\Models\Partenaire;
use App\Services\PartenaireService;
use Illuminate\Http\JsonResponse;

class PartenaireController extends Controller
{
    protected $partenaireService;

    public function __construct(PartenaireService $partenaireService)
    {
        $this->partenaireService = $partenaireService;
    }

    public function index(): JsonResponse
    {
        $partenaires = $this->partenaireService->getAllPartenaires();
        return response()->json($partenaires);
    }

    public function store(PartenaireRequest $request): JsonResponse
    {
        $partenaire = $this->partenaireService->createPartenaire($request->validated());
        return response()->json($partenaire, 201);
    }

    public function show(Partenaire $partenaire): JsonResponse
    {
        $partenaire = $this->partenaireService->getPartenaire($partenaire);
        return response()->json($partenaire);
    }

    public function update(PartenaireRequest $request, Partenaire $partenaire): JsonResponse
    {
        $updated = $this->partenaireService->updatePartenaire($partenaire, $request->validated());
        return response()->json($updated);
    }

    public function destroy(Partenaire $partenaire): JsonResponse
    {
        $this->partenaireService->deletePartenaire($partenaire);
        return response()->json(null, 204);
    }
}