<?php

namespace App\Observers;

use App\Jobs\UpdateProductStockFromFacture;
use App\Models\Facture;
use Illuminate\Support\Facades\Log;

class FactureObserver
{
    /**
     * Handle the Facture "created" event.
     */
    public function created(Facture $facture): void
    {
        // Subtract stock when a facture is created
        UpdateProductStockFromFacture::dispatch($facture->load( 'products'), 'subtract');
    }

    /**
     * Handle the Facture "deleted" event.
     */
    public function deleted(Facture $facture): void
    {
        // Add stock back when a facture is deleted (including soft delete)
        if (!$facture->isForceDeleting()) {
            UpdateProductStockFromFacture::dispatch($facture->load( 'products'), 'add');
        }
    }

    /**
     * Handle the Facture "force deleted" event.
     */
    public function forceDeleted(Facture $facture): void
    {
        // Add stock back when a facture is force deleted
        UpdateProductStockFromFacture::dispatch($facture->load( 'products'), 'add');
    }
}