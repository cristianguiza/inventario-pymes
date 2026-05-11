<?php

namespace App\Http\Controllers;

use App\Models\Movement;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    // Mostrar la vista principal de reportes
    public function index(Request $request)
    {
        // Obtener movimientos con sus relaciones
        $query = Movement::with(['product', 'user'])->orderBy('created_at', 'desc');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $movements = $query->paginate(15);

        // NUEVO: Obtener todos los productos para el select del modal
        $products = \App\Models\Product::orderBy('name')->get();

        return view('reports.index', compact('movements', 'products'));
    }

    // Generar y descargar el reporte en CSV
    public function exportCsv(Request $request)
    {
        $query = Movement::with(['product', 'user'])->orderBy('created_at', 'desc');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $movements = $query->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=reporte_inventario.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($movements) {
            $file = fopen('php://output', 'w');
            // Cabeceras del CSV
            fputcsv($file, ['ID Movimiento', 'Producto', 'Categoría', 'Tipo', 'Cantidad', 'Fecha', 'Usuario']);

            // Filas de datos
            foreach ($movements as $movement) {
                fputcsv($file, [
                    $movement->id,
                    $movement->product->name,
                    $movement->product->category->name ?? 'N/A',
                    ucfirst($movement->type),
                    $movement->quantity,
                    $movement->date,
                    $movement->user->name
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
