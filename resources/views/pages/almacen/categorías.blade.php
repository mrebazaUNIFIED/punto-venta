<x-app-layout>
    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-4xl font-black text-slate-950 tracking-tighter">Categorías de Artículos</h2>
                <p class="text-slate-400 font-medium mt-1">Organiza tu inventario mediante grupos lógicos y
                    descriptivos.</p>
            </div>
            <button id="openCreateModal"
                class="group flex items-center justify-center gap-3 px-8 py-4 bg-brand hover:bg-brand-hover text-white rounded-[2rem] font-black uppercase tracking-[0.2em] text-xs shadow-2xl shadow-blue-200 transition-all active:scale-95">
                <i class="fas fa-plus text-lg group-hover:rotate-90 transition-transform"></i>
                Nueva Categoría
            </button>
        </div>

        <!-- Search & Info Strip -->
        <div class="flex flex-col md:flex-row items-center gap-6">
            <div class="relative w-full md:w-96 group">
                <div class="absolute inset-y-0 left-6 flex items-center pointer-events-none">
                    <i class="fas fa-search text-slate-300 group-focus-within:text-brand transition-colors"></i>
                </div>
                <input type="text" id="search" placeholder="Filtrar categorías..."
                    class="w-full pl-14 pr-6 py-4 bg-white/70 backdrop-blur-xl border border-white rounded-2xl focus:ring-8 focus:ring-blue-500/5 text-slate-900 font-bold transition-all outline-none placeholder:text-slate-300 shadow-sm">
            </div>

            <div
                class="hidden md:flex items-center gap-3 px-6 py-4 bg-blue-50/50 rounded-2xl border border-blue-100/50 text-blue-600">
                <i class="fas fa-info-circle opacity-50"></i>
                <span class="text-[10px] font-black uppercase tracking-widest italic">Usa el buscador para filtrar
                    rápidamente</span>
            </div>
        </div>

        <!-- Categories Table Card -->
        <div
            class="bg-white/70 backdrop-blur-xl border border-white rounded-[3rem] shadow-[0_40px_80px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-50">
                            <th
                                class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic">
                                #</th>
                            <th
                                class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic">
                                Nombre de Grupo</th>
                            <th
                                class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic">
                                Descripción Detallada</th>
                            <th
                                class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic text-right">
                                Acciones Pro</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="divide-y divide-slate-50">
                        @forelse($categorias as $categoria)
                            <tr class="group hover:bg-slate-50/50 transition-colors" data-id="{{ $categoria->id }}">
                                <td class="px-8 py-6">
                                    <span
                                        class="text-xs font-black text-slate-300">#{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-slate-50 border border-white shadow-sm flex items-center justify-center text-brand group-hover:bg-brand group-hover:text-white transition-all duration-500">
                                            <i class="fas fa-tag"></i>
                                        </div>
                                        <span
                                            class="text-sm font-black text-slate-900 tracking-tight">{{ $categoria->nombre }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span
                                        class="text-xs font-bold text-slate-400 italic line-clamp-1">{{ $categoria->descripcion }}</span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button onclick="openViewModal('{{ $categoria->id }}')"
                                            class="p-2.5 h-10 w-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:bg-blue-50 hover:text-blue-600 transition-all"
                                            title="Ver Detalle">
                                            <i class="fas fa-eye text-sm"></i>
                                        </button>
                                        <button onclick="openEditModal('{{ $categoria->id }}')"
                                            class="p-2.5 h-10 w-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:bg-green-50 hover:text-green-600 transition-all"
                                            title="Editar">
                                            <i class="fas fa-edit text-sm"></i>
                                        </button>
                                        <button onclick="openDeleteModal('{{ $categoria->id }}')"
                                            class="p-2.5 h-10 w-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:bg-red-50 hover:text-red-600 transition-all"
                                            title="Eliminar">
                                            <i class="fas fa-trash-alt text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center gap-4 opacity-20">
                                        <i class="fas fa-list text-6xl"></i>
                                        <p class="text-sm font-black uppercase tracking-widest italic">No hay categorías
                                            registradas</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categorias->hasPages())
                <div class="px-8 py-6 border-t border-slate-50 bg-slate-50/30">
                    {{ $categorias->links() }}
                </div>
            @endif
        </div>

        <!-- Modales Core Logic -->

        <!-- Modal Crear -->
        <div id="createModal"
            class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
            <div
                class="bg-white rounded-[3rem] p-10 w-full max-w-lg shadow-[0_50px_100px_rgba(0,0,0,0.15)] relative animate-in zoom-in-95 duration-300">
                <button id="closeCreateModal"
                    class="absolute top-8 right-8 w-10 h-10 rounded-2xl bg-slate-50 text-slate-400 hover:bg-red-50 hover:text-red-600 transition-colors flex items-center justify-center">
                    <i class="fas fa-times text-lg"></i>
                </button>

                <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-10 italic">Nueva Categoría
                    Pro</h3>

                <form id="createForm" method="POST" action="{{ route('almacen.categorias.store') }}" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic block text-left">Nombre
                            de Categoría</label>
                        <input type="text" name="nombre" placeholder="Ej: Bebidas, Snacks..."
                            class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-[8px] focus:ring-blue-500/5 text-slate-900 font-bold transition-all outline-none">
                    </div>
                    <div class="space-y-2 text-left">
                        <label
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic block text-left">Descripción
                            (Opcional)</label>
                        <textarea name="descripcion" rows="3"
                            placeholder="Describe brevemente el contenido de esta categoría..."
                            class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-[8px] focus:ring-blue-500/5 text-slate-900 font-bold transition-all outline-none resize-none"></textarea>
                    </div>
                    <div class="pt-6">
                        <button type="submit"
                            class="w-full py-5 bg-brand hover:bg-brand-hover text-white rounded-[2rem] font-black uppercase tracking-[0.2em] text-xs shadow-2xl shadow-blue-100 transition-all active:scale-95">
                            Registrar Categoría
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Ver -->
        <div id="viewModal"
            class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
            <div
                class="bg-white rounded-[3rem] p-10 w-full max-w-lg shadow-[0_50px_100px_rgba(0,0,0,0.15)] relative animate-in zoom-in-95 duration-300 text-center">
                <button id="closeViewModal"
                    class="absolute top-8 right-8 w-10 h-10 rounded-2xl bg-slate-50 text-slate-400 hover:bg-red-50 hover:text-red-600 transition-colors flex items-center justify-center">
                    <i class="fas fa-times text-lg"></i>
                </button>

                <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-10 italic">Detalle de
                    Categoría</h3>

                <div
                    class="w-24 h-24 mx-auto mb-6 rounded-[2rem] bg-blue-50 text-brand flex items-center justify-center text-4xl shadow-inner">
                    <i class="fas fa-tag"></i>
                </div>

                <h4 id="viewNombre" class="text-2xl font-black text-slate-900 tracking-tight mb-4 uppercase">---</h4>
                <div class="bg-slate-50 p-6 rounded-3xl text-left border border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 italic">Descripción
                        Registrada:</p>
                    <p id="viewDescripcion" class="text-sm font-bold text-slate-600 leading-relaxed italic">---</p>
                </div>

                <div class="mt-8">
                    <button onclick="toggleModal('viewModal', false)"
                        class="px-8 py-4 bg-slate-900 text-white rounded-[1.5rem] font-black uppercase tracking-[0.2em] text-[10px]">Entendido</button>
                </div>
            </div>
        </div>

        <!-- Modal Editar -->
        <div id="editModal"
            class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
            <div
                class="bg-white rounded-[3rem] p-10 w-full max-w-lg shadow-[0_50px_100px_rgba(0,0,0,0.15)] relative animate-in zoom-in-95 duration-300">
                <button id="closeEditModal"
                    class="absolute top-8 right-8 w-10 h-10 rounded-2xl bg-slate-50 text-slate-400 hover:bg-red-50 hover:text-red-600 transition-colors flex items-center justify-center">
                    <i class="fas fa-times text-lg"></i>
                </button>

                <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-10 italic">Modificar
                    Categoría</h3>

                <form id="editForm" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editId" name="id">
                    <div class="space-y-2">
                        <label
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic block text-left">Nombre
                            Actualizado</label>
                        <input type="text" id="editNombre" name="nombre"
                            class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-[8px] focus:ring-blue-500/5 text-slate-900 font-bold transition-all outline-none">
                    </div>
                    <div class="space-y-2">
                        <label
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic block text-left">Nueva
                            Descripción</label>
                        <textarea id="editDescripcion" name="descripcion" rows="3"
                            class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-[8px] focus:ring-blue-500/5 text-slate-900 font-bold transition-all outline-none resize-none"></textarea>
                    </div>
                    <div class="pt-6">
                        <button type="submit" id="btnGuardarEdit"
                            class="w-full py-5 bg-green-600 hover:bg-slate-950 text-white rounded-[2rem] font-black uppercase tracking-[0.2em] text-xs shadow-2xl shadow-green-100 transition-all active:scale-95">
                            Confirmar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Eliminar -->
        <div id="deleteModal"
            class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
            <div
                class="bg-white rounded-[3rem] p-10 w-full max-w-lg shadow-[0_50px_100px_rgba(0,0,0,0.15)] relative animate-in zoom-in-95 duration-300 text-center">
                <h3 class="text-xs font-black text-red-500 uppercase tracking-[0.3em] mb-10 italic">Zona de Riesgo</h3>

                <div
                    class="w-20 h-20 mx-auto mb-6 rounded-[2rem] bg-red-50 text-red-500 flex items-center justify-center text-3xl">
                    <i class="fas fa-trash-alt"></i>
                </div>

                <h4 class="text-xl font-black text-slate-900 italic">¿Eliminar esta agrupación?</h4>
                <p class="text-slate-400 font-bold text-sm mt-2 leading-relaxed px-6">
                    Se borrará la categoría "<span id="deleteName"
                        class="text-red-700 underline font-black">---</span>". Asegúrate de que no tenga artículos
                    asociados.
                </p>

                <form id="deleteForm" method="POST" class="mt-10">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="deleteFormId" name="id">
                    <div class="flex gap-4">
                        <button type="button" id="closeDeleteModal"
                            class="flex-1 py-5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-[2rem] font-black uppercase tracking-[0.2em] text-[10px] transition-all">
                            Mejor no
                        </button>
                        <button type="submit"
                            class="flex-1 py-5 bg-red-600 hover:bg-red-700 text-white rounded-[2rem] font-black uppercase tracking-[0.2em] text-[10px] transition-all shadow-2xl shadow-red-100">
                            Sí, Eliminar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // ======================== CORE MODALS ========================
        function toggleModal(id, show) {
            const modal = document.getElementById(id);
            if (show) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            } else {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = 'auto';
            }
        }

        document.getElementById('openCreateModal').addEventListener('click', () => toggleModal('createModal', true));
        document.getElementById('closeCreateModal').addEventListener('click', () => toggleModal('createModal', false));
        document.getElementById('closeViewModal').addEventListener('click', () => toggleModal('viewModal', false));
        document.getElementById('closeEditModal').addEventListener('click', () => toggleModal('editModal', false));
        document.getElementById('closeDeleteModal').addEventListener('click', () => toggleModal('deleteModal', false));

        // Cierre con clic fuera
        document.querySelectorAll('.fixed').forEach(modal => {
            modal.addEventListener('click', e => {
                if (e.target === modal) toggleModal(modal.id, false);
            });
        });

        // ======================== ACTIONS ========================
        function openViewModal(id) {
            const row = document.querySelector(`tr[data-id="${id}"]`);
            if (row) {
                const cells = row.querySelectorAll('td');
                document.getElementById('viewNombre').textContent = cells[1].querySelector('span').textContent;
                document.getElementById('viewDescripcion').textContent = cells[2].querySelector('span').textContent;
                toggleModal('viewModal', true);
            }
        }

        function openEditModal(id) {
            const row = document.querySelector(`tr[data-id="${id}"]`);
            if (row) {
                const cells = row.querySelectorAll('td');
                document.getElementById('editId').value = id;
                document.getElementById('editNombre').value = cells[1].querySelector('span').textContent;
                document.getElementById('editDescripcion').value = cells[2].querySelector('span').textContent;

                const actionUrl = `{{ route('almacen.categorias.update', ['categoria' => ':id']) }}`.replace(':id', id);
                document.getElementById('editForm').action = actionUrl;
                toggleModal('editModal', true);
            }
        }

        document.getElementById('editForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const form = this;
            const url = form.action;
            const button = document.getElementById('btnGuardarEdit');

            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Procesando...';

            fetch(url, {
                method: 'POST',
                body: new FormData(form),
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
                            title: '¡Actualizado!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500,
                            customClass: { popup: 'rounded-[2rem] font-bold' }
                        });

                        toggleModal('editModal', false);
                        const row = document.querySelector(`tr[data-id="${data.categoria.id}"]`);
                        if (row) {
                            row.querySelectorAll('td')[1].querySelector('span').textContent = data.categoria.nombre;
                            row.querySelectorAll('td')[2].querySelector('span').textContent = data.categoria.descripcion;
                            row.classList.add('bg-green-50');
                            setTimeout(() => row.classList.remove('bg-green-50'), 2000);
                        }
                    }
                })
                .finally(() => {
                    button.disabled = false;
                    button.innerHTML = 'Confirmar Cambios';
                });
        });

        function openDeleteModal(id) {
            const row = document.querySelector(`tr[data-id="${id}"]`);
            if (row) {
                document.getElementById('deleteName').textContent = row.querySelectorAll('td')[1].querySelector('span').textContent;
                document.getElementById('deleteFormId').value = id;
                const actionUrl = `{{ route('almacen.categorias.destroy', ['categoria' => ':id']) }}`.replace(':id', id);
                document.getElementById('deleteForm').action = actionUrl;
                toggleModal('deleteModal', true);
            }
        }

        document.getElementById('deleteForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const form = this;
            fetch(form.action, {
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
                            title: 'Eliminado',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false,
                            customClass: { popup: 'rounded-[2rem]' }
                        });
                        toggleModal('deleteModal', false);
                        document.querySelector(`tr[data-id="${data.deleted_id}"]`).remove();
                    }
                });
        });

        // ======================== SEARCH ========================
        document.getElementById('search').addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('#tableBody tr').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    </script>
</x-app-layout>