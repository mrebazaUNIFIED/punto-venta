<x-app-layout>
    <div class="container mx-auto p-4">


        <!-- Tabs -->
        <div class="flex space-x-6 mb-8 border-b border-gray-200">
            <button class="tab-button px-6 py-3 -mb-px text-lg font-semibold text-gray-700 hover:text-gray-400 border-b-2 border-transparent hover:border-gray-400 focus:outline-none transition duration-200" data-tab="list">Lista de Productos</button>
            <button class="tab-button px-6 py-3 -mb-px text-lg font-semibold text-gray-700 hover:text-gray-400 border-b-2 border-transparent hover:border-gray-400 focus:outline-none transition duration-200" data-tab="register">Registrar Producto</button>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Tab: Lista de Artículos -->
            <div id="list" class="tab-pane active">


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
                        id="searchInput"
                        placeholder="Buscar..."
                        class="w-full placeholder-gray-400 pl-10 pr-4 py-2 rounded-full focus:outline-none focus:ring-2 focus:ring-gray-700" />
                </div>

                <div class="overflow-x-auto shadow-lg rounded-lg">
                    <table id="productTable" class="min-w-full bg-white border-collapse">
                        <thead>
                            <tr class="bg-gray-300 ">
                                <th class="py-4 px-6     font-bold uppercase tracking-wide">ID</th>
                                <th class="py-4 px-6    font-bold  tracking-wide">Código</th>
                                <th class="py-4 px-6    font-bold  tracking-wide">Imagen</th>
                                <th class="py-4 px-6    font-bold  tracking-wide">Nombre</th>
                                <th class="py-4 px-6    font-bold  tracking-wide">P. Compra</th>
                                <th class="py-4 px-6    font-bold  tracking-wide">P. Venta</th>
                                <th class="py-4 px-6    font-bold  tracking-wide">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($articulos as $articulo)
                            <tr class="hover:bg-gray-200 transition duration-150 text-center align-middle">
                                <td class="py-3 px-6 text-gray-800 align-middle">{{ $loop->iteration }}</td>
                                <td class="py-3 px-6 text-gray-800 align-middle">{{ $articulo->codigo }}</td>

                                {{-- Imagen centrada horizontal y verticalmente sin distorsión --}}
                                <td class="py-3 px-6 align-middle">
                                    <div class="flex items-center justify-center">
                                        @if ($articulo->imagen)
                                        <img src="{{ asset('storage/articulos/' . $articulo->imagen) }}"
                                            alt="{{ $articulo->nombre }}"
                                            class="h-12 w-12 object-cover rounded" />
                                        @else
                                        <span class="text-gray-500">Sin imagen</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="py-3 px-6 text-gray-800 align-middle">{{ $articulo->nombre }}</td>
                                <td class="py-3 px-6 text-gray-800 align-middle">{{ number_format($articulo->p_compra ?? 0, 2) }}</td>
                                <td class="py-3 px-6 text-gray-800 align-middle">{{ number_format($articulo->p_venta ?? 0, 2) }}</td>

                                {{-- Botones centrados --}}
                                <td class="py-3 px-6 align-middle">
                                    <div class="flex justify-center items-center space-x-2">
                                        <button
                                            class="text-gray-600 hover:text-gray-800 flex items-center"
                                            onclick="openEditModal(this)"
                                            data-id="{{ $articulo->id }}"
                                            data-nombre="{{ $articulo->nombre }}"
                                            data-codigo="{{ $articulo->codigo }}"
                                            data-categoria="{{ $articulo->categoria_id }}"
                                            data-p_compra="{{ $articulo->p_compra }}"
                                            data-p_venta="{{ $articulo->p_venta }}">
                                            <i class="fas fa-edit mr-1"></i>
                                        </button>

                                        <button class="text-red-600 hover:text-red-800 flex items-center"
                                            onclick="openDeleteModal('{{ $articulo->id }}', '{{ $articulo->nombre }}')">
                                            <i class="fas fa-trash mr-1"></i>
                                        </button>
                                    </div>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

                <div class="mt-6 ">
                    {{ $articulos->links() }}
                </div>
            </div>

            <!-- Tab: Registrar Producto -->
            <div id="register" class="tab-pane">
                <form action="{{ route('almacen.articulo.store') }}" method="POST" enctype="multipart/form-data" class="max-w-4xl mx-auto bg-white p-8 shadow-lg rounded-xl space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-semibold">Nombre</label>
                            <input type="text" name="nombre" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold">Código</label>
                            <input type="text" name="codigo" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold">Categoría</label>
                            <select name="categoria_id" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" required>
                                @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold">Imagen</label>
                            <input type="file" name="imagen" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold">Stock</label>
                            <input type="number" name="stock" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold">Precio Compra</label>
                            <input type="number" step="0.01" name="p_compra" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold">Precio Venta</label>
                            <input type="number" step="0.01" name="p_venta" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" required>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                            Registrar
                        </button>
                    </div>
                </form>
            </div>


        </div>

        <!-- Modal para Editar -->
        <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden  items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <h3 class="text-xl font-bold mb-4 text-gray-800">Editar producto</h3>
                <hr class="mb-3">
                <form id="editForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="editId">
                    <div>
                        <label class="block text-gray-700 font-semibold">Nombre</label>
                        <input type="text" name="nombre" id="editNombre" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold">Código</label>
                        <input type="text" name="codigo" id="editCodigo" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold">Categoría</label>
                        <select name="categoria_id" id="editCategoriaId" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold">Imagen</label>
                        <input type="file" name="imagen" id="editImagen" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold">Precio Compra</label>
                        <input type="number" step="0.01" name="p_compra" id="editPCompra" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold">Precio Venta</label>
                        <input type="number" step="0.01" name="p_venta" id="editPVenta" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Cancelar</button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal para Eliminar -->
        <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden  items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <h3 class="text-xl font-bold mb-4 text-gray-800">Eliminar producto</h3>
                <hr class="mb-3">
                <p class="mb-4 text-gray-700">Se va a eliminar el producto: <span id="deleteName" class="font-semibold text-red-700"></p>
                <form id="deleteForm" method="POST" class="space-y-4">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" id="deleteId">
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="closeModal('deleteModal')" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Cancelar</button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(button) {
            document.getElementById('editId').value = button.dataset.id;
            document.getElementById('editNombre').value = button.dataset.nombre;
            document.getElementById('editCodigo').value = button.dataset.codigo;
            document.getElementById('editCategoriaId').value = button.dataset.categoria;
            document.getElementById('editPCompra').value = button.dataset.p_compra;
            document.getElementById('editPVenta').value = button.dataset.p_venta;

            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
        }

        function openDeleteModal(id, nombre) {
            document.getElementById('deleteId').value = id;
            document.getElementById('deleteName').textContent = nombre;

            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.getElementById(modalId).classList.remove('flex');
        }

        document.getElementById('editForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const id = document.getElementById('editId').value;
            const formData = new FormData(this);

            try {
                const response = await fetch(`/almacen/articulos/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    body: formData
                });

                if (response.ok) {
                    closeModal('editModal');
                    Swal.fire({
                        icon: 'success',
                        title: 'Artículo actualizado',
                        text: 'Los datos se guardaron correctamente.',
                        timer: 1500,
                        showConfirmButton: false,
                        iconColor: '#16a34a'
                    }).then(() => location.reload());
                } else {
                    throw new Error('Error al actualizar');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al actualizar el artículo.',
                    iconColor: '#dc2626'
                });
            }
        });

        document.getElementById('deleteForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const url = `/almacen/articulos/${document.getElementById('deleteId').value}`;
            const id = document.getElementById('deleteId').value;

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
                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('_method', 'DELETE');

                    fetch(url, {
                            method: 'POST',
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Eliminado!',
                                    text: data.message || 'El artículo fue eliminado correctamente.',
                                    showConfirmButton: false,
                                    timer: 1500
                                });

                                // Cerrar modal
                                closeModal('deleteModal');

                                // Eliminar la fila de la tabla si tiene el atributo data-id
                                const row = document.querySelector(`tr[data-id="${id}"]`);
                                if (row) row.remove();
                            } else {
                                Swal.fire('Error', data.message || 'No se pudo eliminar el artículo', 'error');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            Swal.fire('Error', 'Error de conexión con el servidor', 'error');
                        });
                }
            });
        });



        document.addEventListener('DOMContentLoaded', () => {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabPanes = document.querySelectorAll('.tab-pane');

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    tabButtons.forEach(btn => btn.classList.remove('bg-gray-900', 'text-white', 'border-blue-500'));
                    tabPanes.forEach(pane => pane.classList.remove('active'));

                    const tabId = button.getAttribute('data-tab');
                    button.classList.add('bg-gray-900', 'text-white', 'border-blue-500');
                    document.getElementById(tabId).classList.add('active');
                });
            });

            if (tabButtons[0]) {
                tabButtons[0].classList.add('bg-gray-900', 'text-white', 'border-blue-500');
            }

        });

        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.trim().toLowerCase();
            const rows = document.querySelectorAll('#productTable tbody tr');

            rows.forEach(row => {
                const code = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const name = row.querySelector('td:nth-child(4)').textContent.toLowerCase();

                if (code.includes(searchTerm) || name.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>

    <style>
        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
        }
    </style>
</x-app-layout>