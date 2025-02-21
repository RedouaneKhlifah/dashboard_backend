<?php

namespace App\Repositories;

use App\Models\Facture;
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
                  ->orWhereHas('order.ticket', function ($q) use ($searchTerm) {
                      $q->whereHas('client', function ($q) use ($searchTerm) {
                          $q->where('first_name', 'like', "%{$searchTerm}%")
                            ->orWhere('last_name', 'like', "%{$searchTerm}%");
                      });
                  })
                  ->orWhereHas('products', function ($q) use ($searchTerm) {
                      $q->where('name', 'like', "%{$searchTerm}%");
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
}