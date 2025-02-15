<?php

namespace App\Repositories;

use App\Models\Devis;

class DevisRepository
{
    protected $model;

    public function __construct(Devis $model)
    {
        $this->model = $model;
    }

    public function getAllWithSearch($searchTerm = null, $perPage = 10)
    {
        $query = $this->model->with(['ticket', 'products' , "client"]);

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('reference', 'like', "%{$searchTerm}%")
                  ->orWhereHas('ticket', function ($q) use ($searchTerm) {
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

    public function find(Devis $devis)
    {
        return $devis->load(['ticket', 'products', 'client']);
    }

    public function create(array $data)
    {
        $devis = $this->model->create($data);
        
        // If products are included in the data, attach them
        if (isset($data['products'])) {
            $products = collect($data['products'])->mapWithKeys(function ($item) {
                return [$item['product_id'] => [
                    'price_unitaire' => $item['price_unitaire'],
                    'quantity' => $item['quantity']
                ]];
            });
            $devis->products()->attach($products);
        }

        return $devis->load(['ticket', 'products']);
    }

    public function update(Devis $devis, array $data)
    {
        $devis->update($data);

        // If products are included in the data, sync them
        if (isset($data['products'])) {
            $products = collect($data['products'])->mapWithKeys(function ($item) {
                return [$item['product_id'] => [
                    'price_unitaire' => $item['price_unitaire'],
                    'quantity' => $item['quantity']
                ]];
            });
            $devis->products()->sync($products);
        }

        return $devis->load(['ticket', 'products']);
    }

    public function delete(Devis $devis)
    {
        return $devis->delete();
    }
}