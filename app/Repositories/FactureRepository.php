<?php

namespace App\Repositories;

use App\Models\Facture;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FactureRepository
{
    protected $model;

    public function __construct(Facture $model)
    {
        $this->model = $model;
    }

    public function getAllWithSearch($searchTerm = null, $perPage = 10)
    {
        $query = $this->model->with(['order', 'products' , "client"]);

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('reference', 'like', "%{$searchTerm}%")
                      ->orWhereHas('client', function ($q) use ($searchTerm) {
                          $q->where('company', 'like', "%{$searchTerm}%");
                      });
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function find(Facture $facture)
    {
        return $facture->load(["order" , "products", "client"]);
    }

    public function create(array $data)
    {
        $facture = $this->model->create($data);
        
        // If products are included in the data, attach them
        if (isset($data['products'])) {
            $products = collect($data['products'])->mapWithKeys(function ($item) {
                return [$item['product_id'] => [
                    'price_unitaire' => $item['price_unitaire'],
                    'quantity' => $item['quantity']
                ]];
            });
            $facture->products()->attach($products);
        }

        return $facture->load(['products' , 'order', 'client']);
    }

    public function update(Facture $facture, array $data)
    {
        // First update the facture model with basic data
        $facture->update($data);
    
        // If products are included in the data, sync them
        if (isset($data['products'])) {
            $products = collect($data['products'])->mapWithKeys(function ($item) {
                // Ensure we're not passing facture_id in the pivot data
                return [$item['product_id'] => [
                    'price_unitaire' => $item['price_unitaire'],
                    'quantity' => $item['quantity'],
                    'order_id' => $item['order_id'] ?? null
                ]];
            });
            
            $facture->products()->sync($products);
        }
    
        // Reload the model with its relationships
        return $facture->fresh(['order', 'products' ,'client']);
    }
    public function delete(Facture $facture)
    {
        return $facture->delete();
    }

    public function getPartialStatusData($startDate, $endDate): array
    {
        $factures = Facture::with('products')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->filter(function ($facture) {
                return  $facture->paid_amount > 0 && ($facture->paid_amount < $facture->totals);
            });



        return [
            'total' => $factures->sum('totals'),
            'paid_amount' => $factures->sum('paid_amount')
        ];
    }

    public function getPaidPartialCompleteSum($startDate, $endDate): float
    {
        return Facture::with('products')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->filter(function ($facture) {
                return $facture->paid_amount > 0;
            })
            ->sum('paid_amount');
    }

    public function getProfit($startDate, $endDate): float
    {     
        $revenue = $this->getRevenue($startDate, $endDate);

        $costProducts = Facture::with(['products']) // No need for `withoutTrashed()` as trashed products are excluded by default
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()       
            ->filter(function ($facture) {
                return $facture->paid_amount > 0;
            })
            ->flatMap(fn(Facture $facture) => $facture->products->map(fn($product) => [
                'profit' =>   $product->cost_price * $product->pivot->quantity,
            ]))
            ->sum('profit');



            Log::info("costProducts : ".  $costProducts);
            Log::info("revenue : ".  $revenue);
            Log::info("profit : ".  ($revenue - $costProducts));
            

            return $revenue - $costProducts;
    }


    public function getRevenue($startDate, $endDate): float
    {
        $revenue = Facture::whereBetween('created_at', [$startDate, $endDate])
            ->get()
        ->filter(function ($facture) {
                return $facture->paid_amount > 0;
            })
            ->sum('totals');
            Log::info("getRevenue revenue ------------- : ".  $revenue);

            return $revenue;
    }

    public function getFacturesForChart($startDate, $endDate): array
    {
        // Get all factures between the provided start and end date
        $factures = Facture::whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->map(function ($facture) {
                return [
                    'date' => $facture->created_at->format('Y-m-d'), // format the date to YYYY-MM-DD
                    'value' => $facture->totals, // or any other relevant metric for the chart
                ];
            });

        return $factures->toArray(); // Return the result in the required format for the chart
    }


}