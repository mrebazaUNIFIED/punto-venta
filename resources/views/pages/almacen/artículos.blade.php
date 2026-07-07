<x-app-layout>
    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-4xl font-black text-slate-950 tracking-tighter">Gestión de Almacén</h2>
                <p class="text-slate-400 font-medium mt-1">Control de inventario y catálogo de productos.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="px-5 py-2.5 bg-white border border-slate-100 rounded-2xl shadow-sm flex items-center gap-3">
                    <i class="fas fa-box text-blue-500"></i>
                    <span
                        class="text-xs font-black text-slate-900 uppercase tracking-widest tabular-nums">{{ $articulos->total() }}
                        <span class="text-slate-400 italic font-medium">Productos</span></span>
                </div>
            </div>
        </div>

        <!-- Modern Tabs -->
        <div
            class="flex items-center gap-2 p-1 bg-slate-100/50 rounded-2xl w-fit border border-slate-200/60 backdrop-blur-sm">
            <button
                class="tab-button px-8 py-3 rounded-xl text-sm font-black uppercase tracking-widest transition-all duration-300 focus:outline-none"
                data-tab="list">
                <i class="fas fa-list-ul mr-2 opacity-50"></i> Lista
            </button>
            <button
                class="tab-button px-8 py-3 rounded-xl text-sm font-black uppercase tracking-widest transition-all duration-300 focus:outline-none"
                data-tab="register">
                <i class="fas fa-plus-circle mr-2 opacity-50"></i> Registro
            </button>
        </div>

        <!-- Tab Content -->
        <div class="tab-content relative">
            <!-- Tab: Lista de Artículos -->
            <div id="list" class="tab-pane active space-y-6">
                <!-- Advanced Search Bar -->
                <div
                    class="bg-white/70 backdrop-blur-xl border border-white p-6 rounded-3xl shadow-sm flex flex-col md:flex-row items-center gap-6">
                    <div class="relative flex-grow group">
                        <div
                            class="absolute inset-y-0 left-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                            <i class="fas fa-search"></i>
                        </div>
                        <input type="text" id="searchInput" placeholder="Filtrar por nombre, código o categoría..."
                            class="w-full pl-14 pr-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-[8px] focus:ring-blue-500/10 text-slate-900 font-bold placeholder:text-slate-300 transition-all outline-none">
                    </div>
                </div>

                <!-- Product Grid Table -->
                <div
                    class="bg-white/70 backdrop-blur-xl border border-white rounded-[2.5rem] shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table id="productTable" class="w-full text-left">
                            <thead
                                class="bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-50">
                                <tr>
                                    <th class="px-8 py-6">ID</th>
                                    <th class="px-8 py-6">Producto</th>
                                    <th class="px-8 py-6">Categoría</th>
                                    <th class="px-8 py-6 text-right">P. Compra</th>
                                    <th class="px-8 py-6 text-right">P. Venta</th>
                                    <th class="px-8 py-6 text-center">Stock</th>
                                    <th class="px-8 py-6 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach ($articulos as $articulo)
                                    <tr class="hover:bg-slate-50/50 transition-all group">
                                        <td class="px-8 py-5 text-xs text-slate-300 italic tabular-nums">
                                            {{ $loop->iteration }}</td>
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="w-14 h-14 rounded-2xl bg-slate-50 border border-slate-100 overflow-hidden flex-shrink-0 group-hover:scale-105 transition-transform duration-300 shadow-inner">
                                                    @if ($articulo->imagen)
                                                        <img src="{{ asset('storage/articulos/' . $articulo->imagen) }}"
                                                            alt="{{ $articulo->nombre }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div
                                                            class="w-full h-full flex items-center justify-center text-slate-200">
                                                            <i class="fas fa-image text-xl"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <span
                                                        class="block text-sm font-black text-slate-900 uppercase tracking-tight">{{ $articulo->nombre }}</span>
                                                    <span
                                                        class="text-[10px] font-bold text-slate-400 uppercase italic tracking-widest">{{ $articulo->codigo }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5">
                                            <span
                                                class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-black uppercase tracking-widest border border-slate-200/50">
                                                {{ $articulo->categoria->nombre ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-5 text-right font-black tabular-nums text-slate-400 text-sm">S/
                                            {{ number_format($articulo->p_compra ?? 0, 2) }}</td>
                                        <td class="px-8 py-5 text-right font-black tabular-nums text-blue-600 text-sm">S/
                                            {{ number_format($articulo->p_venta ?? 0, 2) }}</td>
                                        <td class="px-8 py-5 text-center">
                                            @php $stock = $articulo->inventario->stock ?? 0; @endphp
                                            <span
                                                class="px-3 py-1 rounded-lg text-[10px] font-black tabular-nums {{ $stock > 10 ? 'bg-emerald-50 text-emerald-600' : ($stock > 0 ? 'bg-amber-50 text-amber-600' : 'bg-red-50 text-red-600') }}">
                                                {{ $stock }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div
                                                class="flex justify-center items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <button onclick="openEditModal(this)" data-id="{{ $articulo->id }}"
                                                    data-nombre="{{ $articulo->nombre }}"
                                                    data-codigo="{{ $articulo->codigo }}"
                                                    data-categoria="{{ $articulo->categoria_id }}"
                                                    data-p_compra="{{ $articulo->p_compra }}"
                                                    data-p_venta="{{ $articulo->p_venta }}"
                                                    class="p-2.5 bg-white border border-slate-100 text-slate-400 hover:text-blue-600 hover:border-blue-100 rounded-xl shadow-sm transition-all hover:scale-110 active:scale-95">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button
                                                    onclick="openDeleteModal('{{ $articulo->id }}', '{{ $articulo->nombre }}')"
                                                    class="p-2.5 bg-white border border-slate-100 text-slate-400 hover:text-red-600 hover:border-red-100 rounded-xl shadow-sm transition-all hover:scale-110 active:scale-95">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex justify-center pt-8">
                    {{ $articulos->links() }}
                </div>
            </div>

            <!-- Tab: Registrar Producto -->
            <div id="register" class="tab-pane">
                <div
                    class="max-w-4xl mx-auto bg-white/70 backdrop-blur-xl border border-white p-10 rounded-[3rem] shadow-[0_30px_60px_rgba(0,0,0,0.04)] relative overflow-hidden">
                    <!-- Decoration -->
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-500/5 rounded-full blur-3xl"></div>

                    <h3
                        class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-10 italic border-b border-slate-50 pb-6">
                        Formulario de Registro</h3>

                    <form action="{{ route('almacen.articulo.store') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-8">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 italic">Nombre
                                    del Producto</label>
                                <input type="text" name="nombre" placeholder="Ej: Coca Cola 500ml"
                                    class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-[8px] focus:ring-blue-500/5 text-slate-900 font-bold outline-none transition-all"
                                    required>
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 italic">Código
                                    / SKU</label>
                                <input type="text" name="codigo" placeholder="Ej: BEV-001"
                                    class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-[8px] focus:ring-blue-500/5 text-slate-900 font-bold outline-none transition-all"
                                    required>
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 italic">Categoría</label>
                                <select name="categoria_id"
                                    class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-[8px] focus:ring-blue-500/5 text-slate-900 font-bold outline-none appearance-none"
                                    required>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 italic">Stock
                                    Inicial</label>
                                <input type="number" name="stock" value="0"
                                    class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-[8px] focus:ring-blue-500/5 text-slate-900 font-black tabular-nums outline-none"
                                    required>
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 italic">Precio
                                    Compra (S/)</label>
                                <input type="number" step="0.01" name="p_compra" placeholder="0.00"
                                    class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-[8px] focus:ring-blue-500/5 text-slate-900 font-black tabular-nums outline-none"
                                    required>
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 italic">Precio
                                    Venta (S/)</label>
                                <input type="number" step="0.01" name="p_venta" placeholder="0.00"
                                    class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-[8px] focus:ring-blue-500/5 text-blue-600 font-black tabular-nums outline-none"
                                    required>
                            </div>
                            <div class="md:col-span-2 space-y-2">
                                <label
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 italic">Imagen
                                    del Producto</label>
                                <div class="relative h-32 w-full group">
                                    <input type="file" name="imagen"
                                        class="absolute inset-0 w-full h-full opacity-0 z-10 cursor-pointer">
                                    <div
                                        class="w-full h-full bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl flex flex-col items-center justify-center gap-2 group-hover:bg-slate-100 transition-colors">
                                        <i class="fas fa-cloud-upload-alt text-slate-300 text-2xl"></i>
                                        <span
                                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Click
                                            o Arrastra tu imagen</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pt-6">
                            <button type="submit"
                                class="w-full py-5 bg-blue-600 hover:bg-slate-950 text-white rounded-[2rem] font-black uppercase tracking-[0.2em] text-sm shadow-2xl shadow-blue-200 transition-all active:scale-95 flex items-center justify-center gap-3">
                                <i class="fas fa-save text-lg"></i> Guardar Producto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Glass Modals -->
        <!-- Edit Modal -->
        <div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-950/40 backdrop-blur-sm" onclick="closeModal('editModal')"></div>
            <div
                class="bg-white/90 backdrop-blur-2xl p-8 rounded-[2.5rem] shadow-[0_40px_100px_rgba(0,0,0,0.2)] w-full max-w-md relative animate-in zoom-in-95 duration-300 border border-white">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-lg font-black text-slate-950 tracking-tighter italic">Editar Producto</h3>
                    <button onclick="closeModal('editModal')"
                        class="text-slate-300 hover:text-slate-900 transition-colors">
                        <i class="fas fa-times-circle text-xl"></i>
                    </button>
                </div>
                <form id="editForm" class="space-y-5">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="editId">
                    <div class="space-y-1">
                        <label
                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-2 italic">Nombre</label>
                        <input type="text" name="nombre" id="editNombre"
                            class="w-full px-5 py-3 bg-slate-100/50 border-none rounded-xl focus:ring-4 focus:ring-blue-500/10 font-bold outline-none">
                    </div>
                    <div class="space-y-1">
                        <label
                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-2 italic">Código</label>
                        <input type="text" name="codigo" id="editCodigo"
                            class="w-full px-5 py-3 bg-slate-100/50 border-none rounded-xl focus:ring-4 focus:ring-blue-500/10 font-bold outline-none">
                    </div>
                    <div class="space-y-1">
                        <label
                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-2 italic">Categoría</label>
                        <select name="categoria_id" id="editCategoria"
                            class="w-full px-5 py-3 bg-slate-100/50 border-none rounded-xl focus:ring-4 focus:ring-blue-500/10 font-bold outline-none">
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-2 italic">P.
                                Compra</label>
                            <input type="number" step="0.01" name="p_compra" id="editPCompra"
                                class="w-full px-5 py-3 bg-slate-100/50 border-none rounded-xl focus:ring-4 focus:ring-blue-500/10 font-black tabular-nums outline-none">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-2 italic">P.
                                Venta</label>
                            <input type="number" step="0.01" name="p_venta" id="editPVenta"
                                class="w-full px-5 py-3 bg-slate-100/50 border-none rounded-xl focus:ring-4 focus:ring-blue-500/10 font-black tabular-nums text-blue-600 outline-none">
                        </div>
                    </div>
                    <div class="pt-4 flex gap-3">
                        <button type="button" onclick="closeModal('editModal')"
                            class="flex-1 py-4 bg-slate-100 text-slate-500 rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-slate-200 transition-all">Cancelar</button>
                        <button type="submit"
                            class="flex-[2] py-4 bg-blue-600 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">Guardar
                            Cambios</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Modal -->
        <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-950/40 backdrop-blur-sm" onclick="closeModal('deleteModal')"></div>
            <div
                class="bg-red-50 p-8 rounded-[2.5rem] shadow-2xl w-full max-w-sm relative animate-in zoom-in-95 duration-300 border border-red-100/50">
                <div class="text-center space-y-4">
                    <div
                        class="w-20 h-20 bg-red-100 text-red-600 rounded-3xl flex items-center justify-center mx-auto mb-6 rotate-12 group-hover:rotate-0 transition-transform">
                        <i class="fas fa-exclamation-triangle text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-950 tracking-tighter italic">¿Eliminar Producto?</h3>
                    <p class="text-sm text-slate-400 italic">Estás a punto de eliminar <span id="deleteName"
                            class="font-black text-red-600"></span>. Esta acción no se puede deshacer.</p>
                    <form id="deleteForm" class="pt-6 flex gap-3">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="id" id="deleteId">
                        <button type="button" onclick="closeModal('deleteModal')"
                            class="flex-1 py-4 bg-white text-slate-500 rounded-2xl font-black uppercase tracking-widest text-[10px] border border-slate-100">Ignorar</button>
                        <button type="submit"
                            class="flex-1 py-4 bg-red-600 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-xl shadow-red-100">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(button) {
            document.getElementById('editId').value = button.dataset.id;
            document.getElementById('editNombre').value = button.dataset.nombre;
            document.getElementById('editCodigo').value = button.dataset.codigo;
            document.getElementById('editCategoria').value = button.dataset.categoria;
            document.getElementById('editPCompra').value = button.dataset.p_compra;
            document.getElementById('editPVenta').value = button.dataset.p_venta;
            document.getElementById('editModal').classList.replace('hidden', 'flex');
        }

        function openDeleteModal(id, nombre) {
            document.getElementById('deleteId').value = id;
            document.getElementById('deleteName').textContent = nombre;
            document.getElementById('deleteModal').classList.replace('hidden', 'flex');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.replace('flex', 'hidden');
        }

        // Tab Logic
        document.addEventListener('DOMContentLoaded', () => {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabPanes = document.querySelectorAll('.tab-pane');

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    tabButtons.forEach(btn => btn.classList.remove('bg-white', 'text-slate-950', 'shadow-sm'));
                    tabPanes.forEach(pane => pane.classList.remove('active'));

                    const tabId = button.getAttribute('data-tab');
                    button.classList.add('bg-white', 'text-slate-950', 'shadow-sm');
                    document.getElementById(tabId).classList.add('active');
                });
            });
            tabButtons[0].click();
        });

        // AJAX Edit
        document.getElementById('editForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const id = document.getElementById('editId').value;
            const formData = new FormData(this);
            try {
                const response = await fetch(`/almacen/articulos/${id}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-HTTP-Method-Override': 'PUT' },
                    body: formData
                });
                if (response.ok) {
                    Swal.fire({ icon: 'success', title: 'Producto Actualizado', timer: 1500, showConfirmButton: false }).then(() => location.reload());
                } else {
                    const data = await response.json().catch(() => ({}));
                    const msg = data.message || 'No se pudo actualizar el producto.';
                    Swal.fire({ icon: 'error', title: 'Error de validación', text: msg });
                }
            } catch (error) {
                console.error(error);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Ocurrió un error al actualizar.' });
            }
        });

        // AJAX Delete with SweetAlert2 integration
        document.getElementById('deleteForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const url = `/almacen/articulos/${document.getElementById('deleteId').value}`;
            const formData = new FormData(this);
            fetch(url, { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({ icon: 'success', title: 'Eliminado', showConfirmButton: false, timer: 1500 }).then(() => location.reload());
                    }
                });
        });

        // Clientside Filter
        document.getElementById('searchInput').addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            document.querySelectorAll('#productTable tbody tr').forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(q) ? '' : 'none';
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