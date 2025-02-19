<?php

namespace App\Http\Controllers;

use App\Events\ModelUpdated;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPdfMail;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    protected $orderService;


    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request): JsonResponse
    {
        $searchTerm = $request->query('search');
        $perPage = $request->query('per_page', 10);
        $order = $this->orderService->getAllOrder($searchTerm, $perPage);
        return response()->json($order);
    }

    public function store(OrderRequest $request): JsonResponse
    {
        $order = $this->orderService->createOrder($request->validated());
        broadcast(new ModelUpdated($order, 'order', 'created'));
        return response()->json($order, 201);
    }

    public function show(Order $order): JsonResponse
    {
        $order = $this->orderService->getOrder($order);
        return response()->json($order);
    }

    public function update(OrderRequest $request, Order $devi): JsonResponse
    {
        $order = $this->orderService->updateOrder($devi, $request->validated());
        broadcast(new ModelUpdated($order, 'order', 'updated'));
        return $order
            ? response()->json($order)
            : response()->json(['message' => 'Order not found'], 404);
    }

    public function destroy(Order $devi): JsonResponse
    {
        $success = $this->orderService->deleteOrder($devi);
        broadcast(new ModelUpdated($devi, 'order', 'deleted'));
        return response()->json(null, 204);
    }
    public function sendOrderToEmail(Request $request, Order $devi)
    {
        try {
            $request->validate([
                'htmlContent' => 'required|string'
            ]);
    
            $user = Auth::user();
            $htmlContent = $request->input('htmlContent');    
    
            // Generate PDF
            $pdf = Pdf::loadHTML($htmlContent);
            $pdfContent = $pdf->output();
    
            // Generate a unique file name using reference and current timestamp
            $timestamp = now()->timestamp;
            $pdfFileName = 'order-' . $devi->reference . '-' . $timestamp . '.pdf';
            $pdfPath = "public/order/" . $pdfFileName;
    
            // Store the file using Laravel's Storage
            Storage::put($pdfPath, $pdfContent);
    
            // Send email with the stored file
            Mail::to($user->email)->send(new OrderPdfMail(Storage::path($pdfPath), $devi));
    
            return response()->json([
                'message' => 'Order saved and sent successfully to ' . $user->email,
                'file_url' => asset("storage/order/$pdfFileName") // Public URL
            ]);
    
        } catch (\Exception $e) {
            Log::error('Order email error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'message' => 'Failed to send order',
                'error' => $e->getMessage()
            ], 500);

}
    }
}