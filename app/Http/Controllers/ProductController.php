<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{

    public function index()
{
    $products = Product::all();
    return response()->json($products);
}
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
    ]);
    $product = Product::create($validated);
    return new ProductResource($product);
}

public function update(Request $request, $id)
{
    $product = Product::find($id);
    if (!$product) {
        return response()->json(['error' => 'Product not found'], 404);
    }
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
    ]);
    $product->update($validated);
    return new ProductResource($product);
}

    public function destroy($id)
{

       $product = Product::find($id);
       if (!$product) {
           return response()->json(['error' => 'Product not found'], 404);
       }
    $product->delete();
    return response()->json(['message' => 'Product deleted successfully']);
}
public function search(Request $request)
{
    $query = $request->query('name');
    $products = Product::where('name', 'like', "%{$query}%")->get();
    return response()->json($products);
}

}
