<x-app-layout>
    <div class="p-6 md:p-8 bg-[#F8FAFC] min-h-screen">

        <div class="flex justify-between items-center mb-8">
            <div class="relative w-full max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" placeholder="Buscar un producto por nombre o ID" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-slate-500 focus:border-slate-500 sm:text-sm">
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                <h2 class="text-lg font-semibold text-gray-800">Panel de Reportes</h2>
                
                <form method="GET" action="{{ route('reports.index') }}" class="flex items-center space-x-3">
                    <div class="flex items-center border border-gray-300 rounded-md bg-white px-2 shadow-sm">
                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="border-0 focus:ring-0 text-sm text-gray-600 p-2 bg-transparent">
                        <span class="text-gray-400">-</span>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="border-0 focus:ring-0 text-sm text-gray-600 p-2 bg-transparent">
                    </div>

                    <button type="submit" class="bg-white border border-gray-300 text-gray-700 px-4 py-2.5 rounded-md text-sm font-medium hover:bg-gray-50 transition shadow-sm">
                        Filtrar
                    </button>
                    <a href="{{ route('reports.export', request()->all()) }}" class="bg-[#4A5E6D] text-white px-4 py-2.5 rounded-md text-sm font-medium hover:bg-[#3b4c58] transition shadow-sm flex items-center">
                        Exportar (CSV/PDF)
                    </a>
                    <button 
                      x-data="" 
                      x-on:click.prevent="$dispatch('open-modal', 'registro-movimiento')" 
                      class="bg-[#4A5E6D] text-white px-4 py-2.5 rounded-md text-sm font-medium hover:bg-[#3b4c58] transition shadow-sm flex items-center gap-2">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                      Nuevo Movimiento
                  </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-max">
                    <thead>
                        <tr class="bg-[#F8FAFC] text-gray-600 text-sm border-b border-gray-200">
                            <th class="py-3 px-6 font-semibold">ID</th>
                            <th class="py-3 px-6 font-semibold">Nombre del Producto</th>
                            <th class="py-3 px-6 font-semibold">Categoría</th>
                            <th class="py-3 px-6 font-semibold">Cantidad</th>
                            <th class="py-3 px-6 font-semibold">Estado / Tipo</th>
                            <th class="py-3 px-6 font-semibold">Fecha</th>
                            <th class="py-3 px-6 font-semibold text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700">
                        @forelse ($movements as $index => $movement)
                            <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-[#F8FAFC]' }} border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="py-3 px-6">{{ $movement->id }}</td>
                                <td class="py-3 px-6 font-medium text-gray-900">{{ $movement->product->name }}</td>
                                <td class="py-3 px-6">{{ $movement->product->category->name ?? 'N/A' }}</td>
                                <td class="py-3 px-6 font-medium">{{ $movement->quantity }}</td>
                                
                                <td class="py-3 px-6">
                                    @if($movement->type === 'entrada')
                                        <div class="flex items-center text-green-700 font-medium">
                                            <div class="bg-green-500 rounded-full w-5 h-5 flex items-center justify-center mr-2 shadow-sm">
                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                            </div>
                                            Entrada
                                        </div>
                                    @else
                                        <div class="flex items-center text-red-700 font-medium">
                                            <div class="bg-red-500 rounded-full w-5 h-5 flex items-center justify-center mr-2 shadow-sm">
                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                            </div>
                                            Salida
                                        </div>
                                    @endif
                                </td>
                                
                                <td class="py-3 px-6 text-gray-500">{{ \Carbon\Carbon::parse($movement->date)->format('d/m/Y') }}</td>
                                
                                <td class="py-3 px-6 text-center">
                                    <div class="flex justify-center items-center space-x-3">
                                        <button class="p-1 text-gray-400 hover:text-[#4A5E6D] transition" title="Editar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <button class="p-1 text-gray-400 hover:text-red-500 transition" title="Eliminar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p>No hay movimientos registrados en este periodo.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-white">
                {{ $movements->appends(request()->query())->links() }}
            </div>
            
        </div>
    </div>
    <x-modal name="registro-movimiento" focusable maxWidth="md">
    <div class="p-6 bg-white rounded-xl">
        
        <div class="flex justify-between items-center mb-5 border-b border-gray-100 pb-3">
            <h2 class="text-lg font-semibold text-gray-800">Registrar Movimiento</h2>
            <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form method="POST" action="{{ route('movements.store') }}">
            @csrf <div class="mb-4">
                <label for="product_id" class="block text-sm font-medium text-gray-700 mb-1">Producto</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <select name="product_id" id="product_id" required class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-[#4A5E6D] focus:border-[#4A5E6D] sm:text-sm">
                        <option value="">Seleccione un producto...</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} (Stock: {{ $product->current_stock }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Cantidad</label>
                <input type="number" name="quantity" id="quantity" min="1" required placeholder="Ej: 50" class="block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-[#4A5E6D] focus:border-[#4A5E6D] sm:text-sm">
            </div>

            <div class="mb-4">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Movimiento</label>
                <select name="type" id="type" required class="block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-[#4A5E6D] focus:border-[#4A5E6D] sm:text-sm">
                    <option value="entrada">Entrada</option>
                    <option value="salida">Salida</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Fecha (requerido) *</label>
                <div class="relative">
                    <input type="date" name="date" id="date" required value="{{ date('Y-m-d') }}" class="block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-[#4A5E6D] focus:border-[#4A5E6D] sm:text-sm">
                </div>
            </div>

            <div class="flex justify-end pt-2 border-t border-gray-100">
                <button type="submit" class="bg-[#4A5E6D] w-full text-white px-4 py-2.5 rounded-md text-sm font-medium hover:bg-[#3b4c58] transition shadow-sm text-center">
                    Registrar Movimiento
                </button>
            </div>
        </form>
    </div>
</x-modal>
</x-app-layout>