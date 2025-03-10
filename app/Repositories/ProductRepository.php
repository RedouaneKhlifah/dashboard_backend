<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function getAllWithSearch($searchTerm = null, $perPage = 10 ,$unit)
    {
        $query = $this->model->newQuery();

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('sku', 'like', "%{$searchTerm}%");
            });
        }

        if ($unit) {
            $query->whereRaw('LOWER(unit) = ?', [strtolower($unit)]);
        }

        return $query->orderBy('created_at', 'desc')
                     ->paginate($perPage);
    }

    public function find(Product $product)
    {
        return $product;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(Product $product, array $data)
    {
        $product->update($data);
        return $product;
    }

    public function delete(Product $product)
    {
        return $product->delete();
    }
}