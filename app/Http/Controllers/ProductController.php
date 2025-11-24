<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
   
    public function index_dashboard()
    {

        $products = Product::with('category')->orderBy('created_at', 'desc')->paginate(5);

        $allProducts = Product::all();
        $categories = Category::all();


        $totalProducts = $allProducts->count(); 
        $totalCategories = $categories->count(); 
        

        $lowStockCount = $allProducts->where('stock_quantity', '<', 20)->count();

  
        $totalStockValue = $allProducts->sum(function ($product) {
            return $product->price * $product->stock_quantity;
        });
        
        $stats = [
            'totalProducts' => $totalProducts,
            'totalCategories' => $totalCategories,
            'lowStockCount' => $lowStockCount,
            'totalStockValue' => $totalStockValue,
        ];

        return view('dashboard', compact('products', 'categories', 'stats'));
    }


    public function index()
    {
  
        $products = Product::with('category')->orderBy('name')->paginate(15);
        $categories = Category::all(); 
        return view('products.index', compact('products', 'categories'));
    }
    

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'New product added successfully!');
    }

 
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

  
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'sku' => ['required', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($product->id)],
            'price' => ['required', 'numeric', 'min:0.01'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }


    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}