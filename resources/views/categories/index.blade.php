<x-app-layout>
    <div class="p-6 md:p-8 bg-[#F8FAFC] min-h-screen">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Categorías</h1>
            <form action="{{ route('categories.store') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="text" name="name" placeholder="Nueva categoría" class="rounded-md border-gray-300 shadow-sm focus:ring-[#4A5E6D]" required>
                <button type="submit" class="bg-[#4A5E6D] text-white px-4 py-2 rounded-md hover:bg-[#3b4c58]">Añadir</button>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden max-w-2xl">
            <table class="w-full text-left">
                <thead class="bg-[#F8FAFC] text-gray-600 border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-6 font-semibold">Nombre</th>
                        <th class="py-3 px-6 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach($categories as $category)
                    <tr class="border-b border-gray-100">
                        <td class="py-3 px-6">{{ $category->name }}</td>
                        <td class="py-3 px-6 text-center flex justify-center gap-3">
                          <button 
                              x-data="" 
                              x-on:click.prevent="$dispatch('open-modal', 'editar-categoria-{{ $category->id }}')"
                              class="text-gray-400 hover:text-blue-600">
                              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                          </button>

                          <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta categoría?')">
                              @csrf @method('DELETE')
                              <button type="submit" class="text-gray-400 hover:text-red-600">
                                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                              </button>
                          </form>

                          <x-modal name="editar-categoria-{{ $category->id }}" focusable>
                              <form method="POST" action="{{ route('categories.update', $category) }}" class="p-6">
                                  @csrf @method('PATCH')
                                  <h2 class="text-lg font-medium text-gray-900 mb-4">Editar Categoría</h2>
                                  <x-text-input name="name" type="text" class="mt-1 block w-full" value="{{ $category->name }}" required />
                                  <div class="mt-6 flex justify-end">
                                      <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                                      <x-primary-button class="ms-3 bg-[#4A5E6D]">Actualizar</x-primary-button>
                                  </div>
                              </form>
                          </x-modal>
                      </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    
</x-app-layout>