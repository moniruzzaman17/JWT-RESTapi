<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }
    
    public function store(Request $request)
    {
        $user = $this->guard()->user();

        // Check if the user is authorized
        if (!$user || !$user->can('create', Product::class)) {
            return response()->json(['message' => 'Unauthorized! You are not authorize for store product'], 401);
        }

        // Validate request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        // Create and return the new product
        $product = Product::create($validatedData);
        return response()->json($product, 201);
    }
    
    public function update(Request $request, Product $product)
    {
        $user = $this->guard()->user();
    
        // Check if the user is authorized 
        if (!$user || !$user->can('update', $product)) {
            return response()->json(['message' => 'Unauthorized! You are not authorized to update this product.'], 401);
        }
    
        // Validate requesst data
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric',
            'stock' => 'sometimes|required|integer',
        ]);
    
        // Update the product
        $product->update($validatedData);
    
        return response()->json($product);
    }    

    protected function guard()
    {
        // Return the API authentication guard
        return Auth::guard('api');
    }
}
