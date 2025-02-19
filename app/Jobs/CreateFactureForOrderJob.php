<?php

namespace App\Jobs;

use App\Models\Facture;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;



class CreateFactureForOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }


    
    /**
     * Execute the job.
     */
    public function handle()
    
    {
        Log::info('CreateFactureForOrderJob 00000000' . $this->order);

        // Create a order usin
        // g data from the order.
        $facture = Facture::create([
            'order_id'       => $this->order->id,
            'client_id'       => $this->order->client_id,
            'reference'       =>  $this->order->reference,
            'facture_date'      =>  $this->order->order_date,
            'expiration_date' => $this->order->expiration_date, 
            'tva'             => $this->order->tva,
            'remise_type'     => $this->order->remise_type,
            'remise'          => $this->order->remise,
            'note'            => $this->order->note,
        ]);
        
        foreach ($this->order->products as $product) {
            $facture->products()->attach($product->id, [
                'price_unitaire' => $product->pivot->price_unitaire,
                'quantity'       => $product->pivot->quantity,
                'order_id'       => $this->order->id,
            ]);
        }

        Log::info('Facture created successfully for order: ' . $facture);

    }
}
