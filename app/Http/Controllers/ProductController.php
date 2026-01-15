<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class ProductController extends Controller
{
   
    public function index_dashboard()
    {

        $products = Product::with('category')->orderBy('created_at', 'desc')->paginate(10);

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
        $q = request()->query('q');
        $categoryFilter = request()->query('category');

        $productsQuery = Product::with('category')->orderBy('name');

        if ($q) {
            $productsQuery->where(function ($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")
                        ->orWhere('sku', 'like', "%{$q}%");
            });
        }

        if ($categoryFilter) {
            $productsQuery->where('category_id', $categoryFilter);
        }

        $products = $productsQuery->paginate(15)->withQueryString();
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
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('products', 'public');
            if (Schema::hasColumn('products', 'photo_path')) {
                $validated['photo_path'] = $path;
            }
        }

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
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            // delete old photo if exists
            if (Schema::hasColumn('products', 'photo_path') && $product->photo_path) {
                Storage::disk('public')->delete($product->photo_path);
            }

            $path = $request->file('photo')->store('products', 'public');
            if (Schema::hasColumn('products', 'photo_path')) {
                $validated['photo_path'] = $path;
            }
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }


    public function destroy(Product $product)
    {
        // Soft delete only; keep photo so restore can recover it
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product moved to trash.');
    }

    // Show trashed products
    public function trash()
    {
        $q = request()->query('q');
        $categoryFilter = request()->query('category');

        $productsQuery = Product::onlyTrashed()->with('category')->orderBy('deleted_at', 'desc');

        if ($q) {
            $productsQuery->where(function ($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")
                        ->orWhere('sku', 'like', "%{$q}%");
            });
        }

        if ($categoryFilter) {
            $productsQuery->where('category_id', $categoryFilter);
        }

        $products = $productsQuery->paginate(15)->withQueryString();
        $categories = Category::all();

        return view('products.trash', compact('products', 'categories'));
    }

    // Restore a trashed product
    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->route('products.trash')->with('success', 'Product restored successfully!');
    }

    // Permanently delete a trashed product and its photo
    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        if (Schema::hasColumn('products', 'photo_path') && $product->photo_path) {
            Storage::disk('public')->delete($product->photo_path);
        }

        $product->forceDelete();

        return redirect()->route('products.trash')->with('success', 'Product permanently deleted.');
    }

    // Export filtered results to PDF
    public function export(Request $request)
    {
        $q = $request->query('q');
        $categoryFilter = $request->query('category');

        $productsQuery = Product::with('category')->orderBy('name');

        if ($q) {
            $productsQuery->where(function ($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")
                        ->orWhere('sku', 'like', "%{$q}%");
            });
        }

        if ($categoryFilter) {
            $productsQuery->where('category_id', $categoryFilter);
        }

        $products = $productsQuery->get();

        $filename = 'products_' . now()->format('Ymd_His') . '.pdf';

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('products.pdf', compact('products'));
            return $pdf->download($filename);
        }

        // Fallback: return HTML view with PDF content-type suggestion
        return response()->view('products.pdf', compact('products'))->header('Content-Type', 'application/pdf');
    }
}