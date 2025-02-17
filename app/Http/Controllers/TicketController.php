<?php

namespace App\Http\Controllers;

use App\Events\ModelUpdated;
use App\Http\Requests\TicketRequest;
use App\Jobs\CreateDevisForTicketJob;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function index(Request $request): JsonResponse
    {
        $searchTerm = $request->query('search');
        $perPage = $request->query('per_page', 10);
        $tickets = $this->ticketService->getAllTickets($searchTerm, $perPage);
        return response()->json($tickets);
    }

    public function store(TicketRequest $request): JsonResponse
    {
        $ticket = $this->ticketService->createTicket($request->validated());
        broadcast(new ModelUpdated($ticket, 'ticket', 'created'));
        return response()->json($ticket, 201);
    }

    public function show(Ticket $ticket): JsonResponse
    {
        $ticket = $this->ticketService->getTicket($ticket);
        return response()->json($ticket);
    }

    public function update(TicketRequest $request, Ticket $ticket): JsonResponse
    {
        $ticket = $this->ticketService->updateTicket($ticket, $request->validated());
        broadcast(new ModelUpdated($ticket, 'ticket', 'updated'));
        return $ticket
            ? response()->json($ticket)
            : response()->json(['message' => 'Ticket not found'], 404);
    }

    public function destroy(Ticket $ticket): JsonResponse
    {
        $success = $this->ticketService->deleteTicket($ticket);
        broadcast(new ModelUpdated($ticket, 'ticket', 'deleted'));

        return response()->json(null, 204);
    }
}