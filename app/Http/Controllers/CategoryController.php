<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = \App\Models\Category::all();
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:categories|max:255']);
        \App\Models\Category::create($request->all());
        return back()->with('status', 'Categoría creada');
    }

    public function update(Request $request, \App\Models\Category $category)
    {
        $request->validate(['name' => 'required|max:255|unique:categories,name,' . $category->id]);
        $category->update($request->all());
        return back()->with('status', 'Categoría actualizada correctamente');
    }

    public function destroy(\App\Models\Category $category)
    {
        // Verificamos si tiene productos para evitar errores de integridad
        if ($category->products()->count() > 0) {
            return back()->withErrors(['error' => 'No se puede eliminar una categoría que tiene productos asociados.']);
        }

        $category->delete();
        return back()->with('status', 'Categoría eliminada');
    }
}
