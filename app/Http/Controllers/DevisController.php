<?php

namespace App\Http\Controllers;

use App\Events\ModelUpdated;
use App\Http\Requests\DevisRequest;
use App\Models\Devis;
use App\Services\DevisService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\DevisPdfMail;

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
    public function sendDevisToEmail(Request $request, Devis $devi)
    {
        try {
            $request->validate([
                'htmlContent' => 'required|string'
            ]);
    
            $user = Auth::user();
            $htmlContent = $request->input('htmlContent');
    
            Log::info('Devis PDF generation started', ['htmlContent' => $htmlContent]);
    
            // Generate PDF
            $pdf = Pdf::loadHTML($htmlContent);
            $pdfContent = $pdf->output();
    
            Log::info('Devis PDF generated', [
                'pdfSize' => strlen($pdfContent),
                'devisId' => $devi->id,
            ]);
    
            // Save PDF locally
            $pdfFileName = 'devis-' . $devi->reference . '.pdf';
            $pdfPath = storage_path('app/public/devis/' . $pdfFileName);
            
            // Ensure the directory exists
            if (!file_exists(dirname($pdfPath))) {
                mkdir(dirname($pdfPath), 0755, true);
            }
    
            // Save the PDF file
            file_put_contents($pdfPath, $pdfContent);
    
            Log::info('PDF saved locally', ['path' => $pdfPath]);
    
            // Send email with the locally stored PDF
            Mail::to($user->email)->send(new DevisPdfMail($pdfPath, $devi));
    
            Log::info('Email sent successfully', ['email' => $user->email]);
    
            return response()->json([
                'message' => 'Devis sent successfully to ' . $user->email
            ]);
    
        } catch (\Exception $e) {
            Log::error('Devis email error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'message' => 'Failed to send devis',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}