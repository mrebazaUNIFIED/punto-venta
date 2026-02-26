<x-app-layout>
    <div class="p-6">
        <h1 class="text-2xl font-bold  mb-6">Inventario</h1>

        <div class="flex justify-between items-center mb-4 flex-wrap gap-2">
            <div class="flex space-x-2">
                <a href="{{ route('almacen.inventario.excel') }}" class="bg-green-600 text-white px-3 py-2 rounded hover:bg-green-700 text-sm flex items-center gap-1">
                    <i class="fa-solid fa-file-excel"></i> Excel
                </a>
                <a href="{{ route('almacen.inventario.pdf') }}" class="bg-red-600 text-white px-3 py-2 rounded hover:bg-red-700 text-sm flex items-center gap-1">
                    <i class="fa-solid fa-file-pdf"></i> PDF
                </a>
            </div>

            <!-- Buscador con icono -->
            <div class="flex items-center relative w-full md:w-1/3">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103 10.5a7.5 7.5 0 0013.15 6.15z" />
                    </svg>
                </span>
                <input type="text" id="search" placeholder="Buscar por código o nombre" class="w-full pl-10 pr-4 py-2 rounded-full border border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-700">
            </div>
        </div>

        <div class="overflow-x-auto  rounded-lg shadow">
            <table class="min-w-full bg-white divide-y divide-gray-200">
                <thead class=" bg-gray-300 ">
                    <tr class=" text-center ">
                        <th class="px-4 py-3 tracking-wider">Imagen</th>
                        <th class="px-4 py-3 tracking-wider ">Código</th>
                        <th class="px-4 py-3 tracking-wider">Producto</th>
                        <th class="px-4 py-3 tracking-wider">Precio Compra</th>
                        <th class="px-4 py-3 tracking-wider">Precio Venta</th>
                        <th class="px-4 py-3 tracking-wider">Stock</th>
                        <th class="px-4 py-3 tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y  divide-gray-200">
                    @foreach($inventario as $item)
                    <tr class="hover:bg-gray-200 transition duration-150 text-center">
                        <td class="px-4 py-3 align-middle">
                            <div class="flex items-center justify-center">
                                @if($item->articulo->imagen)
                                <img src="{{ asset('storage/articulos/' . $item->articulo->imagen) }}" alt="Producto" class="w-10 h-10 rounded-md">
                                @else
                                <span class="text-gray-500">No img</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3">{{ $item->articulo->codigo }}</td>
                        <td class="px-4 py-3">{{ $item->articulo->nombre }}</td>
                        <td class="px-4 py-3">S/ {{ number_format($item->articulo->p_compra, 2) }}</td>
                        <td class="px-4 py-3">S/ {{ number_format($item->articulo->p_venta, 2) }}</td>
                        <td class="px-4 py-3">
                            <input type="number" id="stock-{{ $item->id }}" class="w-24 px-2 py-1 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none text-center" value="{{ $item->stock }}" data-id="{{ $item->id }}">
                        </td>
                        <td class="px-4 py-3">
                            <button onclick="actualizarStock('{{ $item->id }}')" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-sm">
                                Actualizar
                            </button>
                            <span id="estado-{{ $item->id }}" class="text-sm text-gray-600 ml-2"></span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="p-4">
                {{ $inventario->links() }}
            </div>
        </div>
    </div>


    <script>
        function actualizarStock(id) {
            const input = document.getElementById('stock-' + id);
            const stock = input.value;
            const estadoSpan = document.getElementById('estado-' + id);

            fetch(`/almacen/inventario/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        _method: 'PUT',
                        stock: stock
                    })
                })
                .then(res => res.json())
                .then(data => {
                 

                    Swal.fire({
                        icon: 'success',
                        title: 'Stock actualizado',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    });

                    setTimeout(() => {
                        estadoSpan.innerText = '';
                        input.classList.remove('border-green-500');
                        input.classList.add('border-gray-600');
                    }, 2000);
                })
                .catch(error => {
                    input.classList.add('border-red-500');

                    Swal.fire({
                        icon: 'error',
                        title: 'Error al actualizar',
                        text: 'Intenta nuevamente más tarde.'
                    });

                    console.error('Error al actualizar:', error);
                });
        }

        // Buscador local dinámico
        document.getElementById('search').addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#tableBody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    </script>
</x-app-layout>