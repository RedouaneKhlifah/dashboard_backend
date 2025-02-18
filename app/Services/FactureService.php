<?php

namespace App\Services;

use App\Models\Facture;
use App\Repositories\FactureRepository;

class FactureService
{
    protected $repository;

    public function __construct(FactureRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllFacture($searchTerm = null, $perPage = 10)
    {
        $factureCollection = $this->repository->getAllWithSearch($searchTerm, $perPage);
        // Format the products array for each Facture
        $factureCollection->getCollection()->transform(function ($facture) {
            return $this->formatProducts($facture);
        });
        
        return $factureCollection;
    }

    public function getFacture(Facture $facture)
    {
      
       return  $facture = $this->repository->find($facture);
    }

    public function createFacture(array $data)
    {
        $facture =  $this->repository->create($data);
        return $this->formatProducts($facture);
    }

    public function updateFacture(Facture $facture, array $data)
    {
        $updatedFacture = $this->repository->update($facture, $data);
        return $this->formatProducts($updatedFacture);

    }

    public function deleteFacture(Facture $facture)
    {
        return $this->repository->delete($facture);
    }

    public function formatProducts(Facture $facture): Facture
    {
        $formattedProducts = $facture->products->map(function ($product) {
            $productArray = $product->toArray();
            $pivotData = $productArray['pivot'];

            // Remove the pivot entry
            unset($productArray['pivot']);

            // Merge pivot data into the main product array
            return array_merge($productArray, $pivotData);
        });

        // Update the products relation in the Facture model
        $facture->setRelation('products', $formattedProducts);

        return $facture;
    }
}
