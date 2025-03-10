<?php

namespace App\Jobs;

use App\Models\Facture;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateProductStockFromFacture implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Facture $facture, public string $action)
    {
    }


    public function handle(): void
    {
        Log::info("Updating stock for product " . $this->facture->products);
        // Ensure products are loaded

        $this->facture->products->each(function ($product) {
            $quantity = $product->pivot->quantity;

            Log::info("Updating stock for product {$product->id} by {$quantity}");

            if ($this->action === 'subtract') {
                $product->decrement('stock', $quantity);
            } else {
                $product->increment('stock', $quantity);
            }
        });
    }
}