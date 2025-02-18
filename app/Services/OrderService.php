<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\OrderRepository;

class OrderService
{
    protected $repository;

    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllOrder($searchTerm = null, $perPage = 10)
    {
        $orderCollection = $this->repository->getAllWithSearch($searchTerm, $perPage);
        // Format the products array for each Order
        $orderCollection->getCollection()->transform(function ($order) {
            return $this->formatProducts($order);
        });
        
        return $orderCollection;
    }

    public function getOrder(Order $order)
    {
       $order = $this->repository->find($order);
       return $this->formatProducts($order); 
    }

    public function createOrder(array $data)
    {
        $order =  $this->repository->create($data);
        return $this->formatProducts($order);
    }

    public function updateOrder(Order $order, array $data)
    {
        $updatedOrder = $this->repository->update($order, $data);
        return $this->formatProducts($updatedOrder);

    }

    public function deleteOrder(Order $order)
    {
        return $this->repository->delete($order);
    }

    public function formatProducts(Order $order): Order
    {
        $formattedProducts = $order->products->map(function ($product) {
            $productArray = $product->toArray();
            $pivotData = $productArray['pivot'];

            // Remove the pivot entry
            unset($productArray['pivot']);

            // Merge pivot data into the main product array
            return array_merge($productArray, $pivotData);
        });

        // Update the products relation in the Order model
        $order->setRelation('products', $formattedProducts);

        return $order;
    }
}
