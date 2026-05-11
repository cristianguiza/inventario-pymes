<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = \App\Models\Product::with('category')->paginate(10);
        $categories = \App\Models\Category::all(); // Para el modal de creación
        return view('products.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'min_stock_alert' => 'required|integer|min:0',
        ]);

        \App\Models\Product::create($request->all());
        return back()->with('status', 'Producto creado con éxito');
    }

    public function destroy(\App\Models\Product $product)
    {
        $product->delete();
        return back()->with('status', 'Producto eliminado');
    }
    public function update(Request $request, \App\Models\Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'min_stock_alert' => 'required|integer|min:0',
        ]);

        $product->update($request->all());
        return back()->with('status', 'Producto actualizado correctamente');
    }
}
