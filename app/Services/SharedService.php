<?php

namespace App\Services;

class SharedService
{
    public function __construct()
    {
    }

    public function formatProducts($modal)
    {
        $formattedProducts = $modal->products->map(function ($product) {
            $productArray = $product->toArray();
            $pivotData = $productArray['pivot'];

            // Remove the pivot entry
            unset($productArray['pivot']);

            // Merge pivot data into the main product array
            return array_merge($productArray, $pivotData);
        });

        $modal->setRelation('products', $formattedProducts);

        return $modal;
    }

}
