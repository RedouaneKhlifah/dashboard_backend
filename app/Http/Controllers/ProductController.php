<?php

namespace App\Http\Controllers;

use App\Events\ModelUpdated;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request): JsonResponse
    {
        $searchTerm = $request->query('search');
        $perPage = $request->query('per_page', 10);
        $products = $this->productService->getAllProducts($searchTerm, $perPage);
        return response()->json($products);
    }

    public function store(ProductRequest $request): JsonResponse
    {
        $product = $this->productService->createProduct($request->validated() , $request->file('image'));
        broadcast(new ModelUpdated($product, 'product', 'created'));
        return response()->json($product, 201);
    }
    
    public function show(Product $product): JsonResponse
    {
        $product = $this->productService->getProduct($product);
        return response()->json($product);
    }

    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        $product = $this->productService->updateProduct($product, $request->validated(), $request->file('image'));
        broadcast(new ModelUpdated($product, 'product', 'updated'));

        return $product
            ? response()->json($product)
            : response()->json(['message' => 'Product not found'], 404);
    }

    public function destroy(Product $product): JsonResponse
    {
        $success = $this->productService->deleteProduct($product);
        broadcast(new ModelUpdated($product, 'product', 'deleted'));

        return response()->json(null, 204);
    }
}