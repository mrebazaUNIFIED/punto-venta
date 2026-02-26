<x-app-layout>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Categorías</h2>

        <!-- Buscador -->
        <div class="flex items-center mb-6 relative w-full md:w-1/3">
            <!-- Ícono -->
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103 10.5a7.5 7.5 0 0013.15 6.15z" />
                </svg>
            </span>

            <!-- Input -->
            <input
                type="text"
                id="search"
                placeholder="Buscar..."
                class="w-full   placeholder-gray-400 pl-10 pr-4 py-2 rounded-full focus:outline-none focus:ring-2 focus:ring-gray-700" />
        </div>

        <!-- Botón para agregar nueva categoría -->
        <button id="openCreateModal" class="bg-blue-600 text-white px-4 py-2 rounded-md mb-6 hover:bg-blue-700 flex items-center transition duration-200">
            <i class="fas fa-plus mr-2"></i> Agregar nueva categoría
        </button>

        <!-- Tabla de categorías -->
        <div class="overflow-x-auto shadow-md rounded-lg">
            <table class="min-w-full bg-white divide-y divide-gray-200">
                <thead class="bg-gray-300">
                    <tr class="text-center">
                        <th class="px-6 py-3 font-bold   tracking-wider">N°</th>
                        <th class="px-6 py-3 font-bold   tracking-wider">Nombre</th>
                        <th class="px-6 py-3 font-bold   tracking-wider">Descripción</th>
                        <th class="px-6 py-3 font-bold   tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="bg-white divide-y divide-gray-200">
                    @foreach ($categorias as $categoria)
                    <tr class="hover:bg-gray-200 transition duration-150 text-center" data-id="{{ $categoria->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $categoria->nombre }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $categoria->descripcion }}</td>
                        <td class="px-6 py-4 whitespace-nowrap flex items-center justify-center space-x-2">
                            <button onclick="openViewModal('{{ $categoria->id }}')" class="text-blue-600 hover:text-blue-800 flex items-center">
                                <i class="fas fa-eye mr-1"></i>
                            </button>
                            <button onclick="openEditModal('{{ $categoria->id }}')" class="text-gray-600 hover:text-gray-800 flex items-center">
                                <i class="fas fa-edit mr-1"></i>
                            </button>
                            <button onclick="openDeleteModal('{{ $categoria->id }}')" class="text-red-600 hover:text-red-800 flex items-center">
                                <i class="fas fa-trash mr-1"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-6 ">
            {{ $categorias->links() }}
        </div>

        <!-- Modal Crear -->
        <div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
                <h3 class="text-xl font-bold mb-4 flex items-center justify-center text-gray-800">
                    Nueva categoría
                </h3>
                <form id="createForm" method="POST" action="{{ route('almacen.categorias.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="border border-gray-300 p-2 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea id="descripcion" name="descripcion" class="border border-gray-300 p-2 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" id="closeCreateModal" class="bg-gray-500 text-white px-4 py-2 rounded-md mr-2 hover:bg-gray-600 flex items-center transition duration-200">
                            <i class="fas fa-times mr-2"></i> Cancelar
                        </button>
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 flex items-center transition duration-200">
                            <i class="fas fa-save mr-2"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Ver -->
        <div id="viewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden  items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
                <h3 class="text-xl font-bold mb-4 flex items-center text-gray-800">
                   Ver Categoría
                </h3>
                <hr class="mb-3">
                <div id="viewContent">
                    <p class="mb-2 hidden"><strong class="text-gray-700">ID:</strong> <span id="viewId" class="text-gray-900"></span></p>
                    <p class="mb-2"><strong class="text-gray-700">Nombre:</strong> <span id="viewNombre" class="text-gray-900"></span></p>
                    <p><strong class="text-gray-700">Descripción:</strong> <span id="viewDescripcion" class="text-gray-900"></span></p>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="button" id="closeViewModal" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 flex items-center transition duration-200">
                      Cerrar
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Editar -->
        <div id="editModal" class="fixed inset-0 bg-gray-600 hidden bg-opacity-50   items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
                <h3 class="text-xl font-bold mb-4 flex items-center text-gray-800">
                   Editar Categoría
                </h3>
                <hr class="mb-3">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editId" name="id">
                    <div class="mb-4">
                        <label for="editNombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" id="editNombre" name="nombre" class="border border-gray-300 p-2 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="editDescripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea id="editDescripcion" name="descripcion" class="border border-gray-300 p-2 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" id="closeEditModal" class="bg-gray-500 text-white px-4 py-2 rounded-md mr-2 hover:bg-gray-600 flex items-center transition duration-200">
                            Cancelar
                        </button>
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 flex items-center transition duration-200">
                             Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Eliminar -->
        <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden  items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
                <h3 class="text-xl font-bold mb-4 flex items-center text-gray-800">
                    Eliminar Categoría
                </h3>
                <hr class="mb-3">
                <p class="text-gray-700">Se va a eliminar la categoría: <span id="deleteName" class="font-semibold text-red-700"></span></p>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="deleteFormId" name="id">
                    <div class="flex justify-end mt-4">
                        <button type="button" id="closeDeleteModal" class="bg-gray-500 text-white px-4 py-2 rounded-md mr-2 hover:bg-gray-600 flex items-center transition duration-200">
                             Cancelar
                        </button>
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 flex items-center transition duration-200">
                           Eliminar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // ======================== MODALES ========================
        document.getElementById('openCreateModal').addEventListener('click', () => {
            toggleModal('createModal', true);
        });

        document.getElementById('closeCreateModal').addEventListener('click', () => {
            toggleModal('createModal', false);
        });

        document.getElementById('closeViewModal').addEventListener('click', () => {
            toggleModal('viewModal', false);
        });

        document.getElementById('closeEditModal').addEventListener('click', () => {
            toggleModal('editModal', false);
        });

        document.getElementById('closeDeleteModal').addEventListener('click', () => {
            toggleModal('deleteModal', false);
        });

        function toggleModal(id, show) {
            const modal = document.getElementById(id);
            if (show) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            } else {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        // Cierre con ESC o clic fuera
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                ['createModal', 'editModal', 'deleteModal', 'viewModal'].forEach(id => toggleModal(id, false));
            }
        });

        document.querySelectorAll('.fixed').forEach(modal => {
            modal.addEventListener('click', e => {
                if (e.target === modal) modal.classList.add('hidden');
            });
        });

        // ======================== VER ========================
        function openViewModal(id) {
            const row = document.querySelector(`tr[data-id="${id}"]`);
            if (row) {
                const cells = row.querySelectorAll('td');
                document.getElementById('viewNombre').textContent = cells[1].textContent;
                document.getElementById('viewDescripcion').textContent = cells[2].textContent;
                toggleModal('viewModal', true);
            } else {
                Swal.fire('Error', 'No se encontró la categoría', 'error');
            }
        }

        // ======================== EDITAR ========================
        function openEditModal(id) {
            const row = document.querySelector(`tr[data-id="${id}"]`);
            if (row) {
                const cells = row.querySelectorAll('td');
                document.getElementById('editId').value = id;
                document.getElementById('editNombre').value = cells[1].textContent;
                document.getElementById('editDescripcion').value = cells[2].textContent;

                const actionUrl = `{{ route('almacen.categorias.update', ['categoria' => ':id']) }}`.replace(':id', id);
                document.getElementById('editForm').action = actionUrl;
                toggleModal('editModal', true);
            } else {
                Swal.fire('Error', 'No se encontró la categoría para editar', 'error');
            }
        }

        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const url = form.action;
            const formData = new FormData(form);
            const button = form.querySelector('button[type="submit"]');

            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Guardando...';

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-HTTP-Method-Override': 'PUT'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Editado!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });

                        toggleModal('editModal', false);
                        const row = document.querySelector(`tr[data-id="${data.categoria.id}"]`);
                        if (row) {
                            const cells = row.querySelectorAll('td');
                            cells[1].textContent = data.categoria.nombre;
                            cells[2].textContent = data.categoria.descripcion;

                            // Animación rápida para destacar cambios
                            row.classList.add('bg-green-100');
                            setTimeout(() => row.classList.remove('bg-green-100'), 1000);
                        }
                    } else {
                        Swal.fire('Error', 'No se pudo actualizar la categoría', 'error');
                    }
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', 'Error de conexión', 'error');
                })
                .finally(() => {
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-save mr-2"></i> Guardar';
                });
        });

        // ======================== ELIMINAR ========================
        function openDeleteModal(id) {
            const row = document.querySelector(`tr[data-id="${id}"]`);
            if (row) {
                const cells = row.querySelectorAll('td');
                document.getElementById('deleteName').textContent = cells[1].textContent;
                document.getElementById('deleteFormId').value = id;

                const actionUrl = `{{ route('almacen.categorias.destroy', ['categoria' => ':id']) }}`.replace(':id', id);
                document.getElementById('deleteForm').action = actionUrl;

                toggleModal('deleteModal', true);
            } else {
                Swal.fire('Error', 'No se encontró la categoría para eliminar', 'error');
            }
        }

        document.getElementById('deleteForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const url = form.action;
            const id = document.getElementById('deleteFormId').value;

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, eliminar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(url, {
                            method: 'POST',
                            body: new FormData(form),
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-HTTP-Method-Override': 'DELETE'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Eliminado!',
                                    text: data.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                toggleModal('deleteModal', false);
                                const row = document.querySelector(`tr[data-id="${data.deleted_id}"]`);
                                if (row) row.remove();
                            } else {
                                Swal.fire('Error', 'No se pudo eliminar', 'error');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            Swal.fire('Error', 'Error de conexión', 'error');
                        });
                }
            });
        });

        // ======================== BUSCADOR ========================
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