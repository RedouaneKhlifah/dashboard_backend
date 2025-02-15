<?php

namespace App\Services;

use App\Models\Devis;
use App\Repositories\DevisRepository;

class DevisService
{
    protected $repository;

    public function __construct(DevisRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllDevis($searchTerm = null, $perPage = 10)
    {
        $devisCollection = $this->repository->getAllWithSearch($searchTerm, $perPage);
        // Format the products array for each Devis
        $devisCollection->getCollection()->transform(function ($devis) {
            return $this->formatProducts($devis);
        });
        
        return $devisCollection;
    }

    public function getDevis(Devis $devis)
    {
       $devis = $this->repository->find($devis);
       return $this->formatProducts($devis); 
    }

    public function createDevis(array $data)
    {
        $devis =  $this->repository->create($data);
        return $this->formatProducts($devis);
    }

    public function updateDevis(Devis $devis, array $data)
    {
        $updatedDevis = $this->repository->update($devis, $data);
        return $this->formatProducts($updatedDevis);

    }

    public function deleteDevis(Devis $devis)
    {
        return $this->repository->delete($devis);
    }

    public function formatProducts(Devis $devis): Devis
    {
        $formattedProducts = $devis->products->map(function ($product) {
            $productArray = $product->toArray();
            $pivotData = $productArray['pivot'];

            // Remove the pivot entry
            unset($productArray['pivot']);

            // Merge pivot data into the main product array
            return array_merge($productArray, $pivotData);
        });

        // Update the products relation in the Devis model
        $devis->setRelation('products', $formattedProducts);

        return $devis;
    }
}
