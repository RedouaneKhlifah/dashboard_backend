<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    protected $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllProducts($searchTerm = null, $perPage = 10)
    {
        $products = $this->repository->getAllWithSearch($searchTerm, $perPage);

        return $products;
    }

    public function getProduct(Product $product)
    {
        $product = $this->repository->find($product);

        return $product;
    }

    public function createProduct(array $data, $imageFile = null)
    {
        // Handle image upload
        if ($imageFile) {
            $data['image'] = $this->storeImage($imageFile);
        }

        // Create the product
        return $this->repository->create($data);
    }

    public function updateProduct(Product $product, array $data, $imageFile = null)
    {
        // Handle image upload
        if ($imageFile) {
            // Delete the old image file (if it exists)
            $this->deleteImage($product->image);

            // Store the new image
            $data['image'] = $this->storeImage($imageFile);
        }

        // Update the product
        return $this->repository->update($product, $data);
    }

    public function deleteProduct(Product $product)
    {
        // Delete the image file (if it exists)
        $this->deleteImage($product->image);

        // Delete the product
        return $this->repository->delete($product);
    }

    /**
     * Store an uploaded image and return the path.
     */
    protected function storeImage($imageFile)
    {
        // Generate a unique filename
        $imageName = uniqid() . '_' . time() . '.' . $imageFile->getClientOriginalExtension();
        
        // Store the file in storage/app/public/product
        $imagePath = $imageFile->storeAs('public/product', $imageName);
        
        // Return the relative path (without the 'public/' prefix)
        return str_replace('public/', '', $imagePath);
    }

    /**
     * Delete an image file from storage.
     */
    protected function deleteImage($imagePath)
    {
        if ($imagePath && Storage::exists('public/' . $imagePath)) {
            Storage::delete('public/' . $imagePath);
        }
    }
}