<?php

namespace App\Http\Controllers;

use App\Models\Movement;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MovementController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'type' => 'required|in:entrada,salida',
            'date' => 'required|date',
        ]);

        // Usamos una transacción para asegurar la integridad de los datos
        DB::transaction(function () use ($request) {
            // 1. Crear el registro del movimiento
            Movement::create([
                'product_id' => $request->product_id,
                'user_id' => auth()->id(),
                'type' => $request->type,
                'quantity' => $request->quantity,
                'date' => $request->date,
            ]);

            // 2. Actualizar el stock en la tabla de productos
            $product = \App\Models\Product::lockForUpdate()->find($request->product_id);

            if ($request->type === 'entrada') {
                $product->current_stock += $request->quantity;
            } else {
                // Opcional: Validar que no se retire más de lo que hay
                $product->current_stock -= $request->quantity;
            }

            $product->save();
        });

        return back()->with('status', 'Movimiento registrado y stock actualizado.');
    }
}
