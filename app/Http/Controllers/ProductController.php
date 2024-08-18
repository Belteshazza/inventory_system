<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_level' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = Product::create($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'product created successfully',
            'data' => $product
        ], 201);
       
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'stock_level' => 'sometimes|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product->update($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'product updated successfully',
            'data' => $product
        ], 200);
    }

    public function index()
    {
        $products = Product::all();
        return response()->json([
            'status' => 'success',
            'data' => $products
        ]);
    }

    public function show($id)
    {
        $product = Product::with('variations')->findOrFail($id);
        return response()->json($product, 200);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'product deleted successfully',
        ], 200);
    }

    public function hide($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_hidden' => true]);
        return response()->json([
            'status' => 'success',
            'message' => 'product hidden successfully',
            'data' => $product
        ], 200);
    }

    public function unhide($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_hidden' => false]);
        return response()->json([
            'status' => 'success',
            'message' => 'product unhidden successfully',
            'data' => $product
        ], 200);
    }

    public function stockNotifications()
    {
        $lowStockProducts = Product::where('stock_level', '<=', 5)->get();
        return response()->json([
            'status' => 'success',
            'message' => 'product stock-notifications',
            'data' => $lowStockProducts
        ], 201);
    }
}
