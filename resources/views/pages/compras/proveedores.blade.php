<x-app-layout>
    <div class="p-6 max-w-5xl mx-auto">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Proveedores</h2>
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-4">


            <form method="GET" action="{{ route('compras.proveedor.index') }}" class="flex gap-2">
                <input type="text" name="search" placeholder="Buscar proveedor..." value="{{ request('search') }}"
                    class="p-2 border rounded w-64">
                <button type="submit" class="bg-gray-300 hover:bg-gray-400 text-sm px-3 py-2 rounded">
                    <i class="fas fa-search"></i>
                </button>
            </form>

            <button onclick="openModal('createModal')" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-plus mr-1"></i> Nuevo Proveedor
            </button>
        </div>



        <div class="overflow-x-auto shadow rounded">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-300">
                    <tr class="font-bold   text-center">
                        <th class="px-6 py-3 tracking-wider">N°</th>
                        <th class="px-6 py-3 tracking-wider ">Nombre</th>
                        <th class="px-6 py-3 tracking-wider ">Teléfono</th>
                        <th class="px-6 py-3 text-center tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-700">
                    @foreach ($proveedores as $proveedor)
                    <tr class="hover:bg-gray-200 transition duration-150 text-center">
                        <td class="px-6 py-4">{{ $proveedores->firstItem() + $loop->index }}</td>
                        <td class="px-6 py-4">{{ $proveedor->nombre }}</td>
                        <td class="px-6 py-4">{{ $proveedor->telefono }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-3">
                                <button onclick='openEditModal(@json($proveedor))' class="text-gray-600 hover:text-gray-800">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="openDeleteModal('{{ $proveedor->id }}', '{{ $proveedor->nombre }}')" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    @if ($proveedores->isEmpty())
                    <tr>
                        <td colspan="4" class="text-center px-6 py-4 text-gray-500">No se encontraron proveedores.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-center">
            {{ $proveedores->links() }}
        </div>
    </div>

    <!-- Modal Crear -->
    <div id="createModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">Nuevo Proveedor</h3>
            <form action="{{ route('compras.proveedor.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" name="nombre" required class="w-full p-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="text" name="telefono" class="w-full p-2 border rounded">
                </div>
                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" onclick="closeModal('createModal')" class="bg-gray-200 px-4 py-2 rounded">Cancelar</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar -->
    <div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">Editar Proveedor</h3>
            <hr class="mb-3">
            <form id="editForm" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" name="nombre" id="editNombre" required class="w-full p-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="text" name="telefono" id="editTelefono" class="w-full p-2 border rounded">
                </div>
                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" onclick="closeModal('editModal')" class="bg-gray-200 px-4 py-2 rounded">Cancelar</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Actualizar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Eliminar -->
    <div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">Eliminar Proveedor</h3>
            <hr class="mb-3">
            <form id="deleteForm" method="POST">
                @csrf @method('DELETE')
                <p class="text-gray-700 mb-4">Se eliminará el proveedor: <span id="deleteProveedorNombre" class="font-semibold text-red-700"></span></p>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal('deleteModal')" class="bg-gray-200 px-4 py-2 rounded">Cancelar</button>
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Eliminar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        function openEditModal(data) {
            if (typeof data === 'string') data = JSON.parse(data);

            const form = document.getElementById('editForm');
            form.action = `/compras/proveedores/${data.id}`;
            document.getElementById('editNombre').value = data.nombre;
            document.getElementById('editTelefono').value = data.telefono ?? '';
            openModal('editModal');
        }

        function openDeleteModal(id, nombre) {
            const form = document.getElementById('deleteForm');
            form.action = `/compras/proveedores/${id}`;
            document.getElementById('deleteProveedorNombre').textContent = nombre;

            // Mostrar el modal
            openModal('deleteModal');

            // Interceptar el botón de enviar
            form.onsubmit = function(e) {
                e.preventDefault(); // Evita envío inmediato

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: `Se eliminará el proveedor "${nombre}"`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Enviar después de confirmación
                    }
                });
            };
        }
    </script>
</x-app-layout>