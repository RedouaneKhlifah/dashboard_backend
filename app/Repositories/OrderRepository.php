<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    protected $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    public function getAllWithSearch($searchTerm = null, $perPage = 10)
    {
        $query = $this->model->with(['ticket', 'products' , "client"]);

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('reference', 'like', "%{$searchTerm}%")
                    ->orWhereHas('client', function ($q) use ($searchTerm) {
                          $q->where('company_ice', 'like', "%{$searchTerm}%");
                      });
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function find(Order $order)
    {
        return $order->load(['ticket', 'products', 'client']);
    }

    public function create(array $data)
    {
        $order = $this->model->create($data);
        
        // If products are included in the data, attach them
        if (isset($data['products'])) {
            $products = collect($data['products'])->mapWithKeys(function ($item) {
                return [$item['product_id'] => [
                    'price_unitaire' => $item['price_unitaire'],
                    'quantity' => $item['quantity']
                ]];
            });
            $order->products()->attach($products);
        }

        return $order->load(['ticket', 'products' , 'client']);
    }

    public function update(Order $order, array $data)
    {
        $order->update($data);

        // If products are included in the data, sync them
        if (isset($data['products'])) {
            $products = collect($data['products'])->mapWithKeys(function ($item) {
                return [$item['product_id'] => [
                    'price_unitaire' => $item['price_unitaire'],
                    'quantity' => $item['quantity'],
                    "ticket_id" => $item['ticket_id']?? null
                ]];
            });
            $order->products()->sync($products);
        }

        return $order->load(['ticket', 'products' , 'client']);
    }

    public function delete(Order $order)
    {
        return $order->delete();
    }

    public function getTopProductsBySoldQuantity($startDate, $endDate): array
    {
    return Product::withTrashed()
        ->select([
            'products.id',
            'products.name',
            DB::raw('SUM(order_product.quantity) as total_sold')
        ])
        ->join('order_product', 'products.id', '=', 'order_product.product_id')
        ->join('orders', 'orders.id', '=', 'order_product.order_id')
        ->whereBetween('orders.order_date', [$startDate, $endDate])
        ->groupBy('products.id', 'products.name')
        ->orderByDesc('total_sold')
        ->limit(10)
        ->get()
        ->map(function ($product) {
            return [
                'name' => $product->name,
                'total_quantity' => (float) $product->total_sold
            ];
        })
        ->toArray();
    }
}