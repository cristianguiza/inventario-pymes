<x-app-layout>
    <div class="p-6 md:p-8 bg-[#F8FAFC] min-h-screen">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Catálogo de Productos</h1>
            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'nuevo-producto')" class="bg-[#4A5E6D] text-white px-4 py-2 rounded-md hover:bg-[#3b4c58] transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nuevo Producto
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-[#F8FAFC] text-gray-600 text-sm border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-6 font-semibold">ID</th>
                        <th class="py-3 px-6 font-semibold">Nombre</th>
                        <th class="py-3 px-6 font-semibold">Categoría</th>
                        <th class="py-3 px-6 font-semibold">Stock Actual</th>
                        <th class="py-3 px-6 font-semibold text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach($products as $index => $product)
                    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-[#F8FAFC]' }} border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-6 text-gray-500">{{ $product->id }}</td>
                        <td class="py-3 px-6 font-medium text-gray-900">{{ $product->name }}</td>
                        <td class="py-3 px-6">{{ $product->category->name }}</td>
                        <td class="py-3 px-6">
                            <span class="px-2 py-1 rounded-full {{ $product->current_stock <= $product->min_stock_alert ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                {{ $product->current_stock }}
                            </span>
                        </td>
                        <td class="py-3 px-6 text-center flex justify-center gap-3">
                          <div class="">
                              <button 
                                  x-data="" 
                                  x-on:click.prevent="$dispatch('open-modal', 'editar-producto-{{ $product->id }}')"
                                  class="text-gray-400 hover:text-blue-600">
                                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                              </button>
                              </div>

                          <x-modal name="editar-producto-{{ $product->id }}" focusable>
                              <form method="POST" action="{{ route('products.update', $product) }}" class="p-6 text-left">
                                  @csrf @method('PATCH')
                                  <h2 class="text-lg font-medium text-gray-900 mb-4 text-center">Editar Producto: {{ $product->name }}</h2>
                                  
                                  <div class="mb-4">
                                      <x-input-label value="Nombre del Producto" />
                                      <x-text-input name="name" type="text" class="mt-1 block w-full" value="{{ $product->name }}" required />
                                  </div>

                                  <div class="mb-4">
                                      <x-input-label value="Categoría" />
                                      <select name="category_id" class="w-full border-gray-300 rounded-md shadow-sm">
                                          @foreach($categories as $category)
                                              <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                                  {{ $category->name }}
                                              </option>
                                          @endforeach
                                      </select>
                                  </div>

                                  <div class="mb-4">
                                      <x-input-label value="Alerta de Stock Mínimo" />
                                      <x-text-input name="min_stock_alert" type="number" class="mt-1 block w-full" value="{{ $product->min_stock_alert }}" required />
                                  </div>

                                  <div class="mt-6 flex justify-end">
                                      <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                                      <x-primary-button class="ms-3 bg-[#4A5E6D]">Guardar Cambios</x-primary-button>
                                  </div>
                              </form>
                          </x-modal>
                                <form action="{{ route('products.destroy', $product) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                </form>
                          </div>
                      </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <x-modal name="nuevo-producto" focusable>
        <form method="POST" action="{{ route('products.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-gray-900 mb-4">Añadir Nuevo Producto</h2>
            
            <div class="mb-4">
                <x-input-label for="name" value="Nombre del Producto" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required />
            </div>

            <div class="mb-4">
                <x-input-label for="category_id" value="Categoría" />
                <select name="category_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <x-input-label for="min_stock_alert" value="Stock Mínimo para Alerta" />
                <x-text-input id="min_stock_alert" name="min_stock_alert" type="number" class="mt-1 block w-full" required />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                <x-primary-button class="ms-3 bg-[#4A5E6D]">Guardar Producto</x-primary-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>