<x-app-layout>
    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-4xl font-black text-slate-950 tracking-tighter italic uppercase">Socios Comerciales</h2>
                <p class="text-slate-400 font-medium mt-1">Directorio de proveedores y canales de abastecimiento verificado.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <button onclick="openModal('createModal')" 
                        class="group flex items-center justify-center gap-3 px-8 py-4 bg-blue-600 hover:bg-slate-950 text-white rounded-[2rem] font-black uppercase tracking-[0.2em] text-xs shadow-2xl shadow-blue-200 transition-all active:scale-95">
                    <i class="fas fa-plus text-lg group-hover:rotate-90 transition-transform"></i>
                    Nuevo Socio
                </button>
            </div>
        </div>

        <!-- Search Bar Strip -->
        <div class="flex justify-start">
            <form method="GET" action="{{ route('compras.proveedor.index') }}" class="relative group w-full md:w-[450px]">
                <div class="absolute inset-y-0 left-6 flex items-center pointer-events-none">
                    <i class="fas fa-search text-slate-300 group-focus-within:text-blue-500 transition-colors"></i>
                </div>
                <input type="text" name="search" placeholder="Filtrar por nombre comercial..." value="{{ request('search') }}"
                       class="w-full pl-14 pr-20 py-4 bg-white/70 backdrop-blur-xl border border-white rounded-[2rem] focus:ring-[8px] focus:ring-blue-500/5 text-slate-900 font-bold transition-all outline-none placeholder:text-slate-300 shadow-sm">
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-2xl bg-slate-900 text-white flex items-center justify-center hover:bg-blue-600 transition-colors">
                    <i class="fas fa-arrow-right text-xs"></i>
                </button>
            </form>
        </div>

        <!-- Suppliers Cards Container -->
        <div class="bg-white/70 backdrop-blur-xl border border-white rounded-[4rem] shadow-[0_40px_80px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-50">
                            <th class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic"># Arq</th>
                            <th class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic">Razón Social / Nombre</th>
                            <th class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic text-center">Contacto Digital</th>
                            <th class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic text-right">Acciones Pro</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($proveedores as $proveedor)
                        <tr class="group hover:bg-blue-50/30 transition-all duration-300">
                            <td class="px-8 py-8">
                                <span class="text-xs font-black text-slate-300 italic">SOC-{{ str_pad($proveedores->firstItem() + $loop->index, 3, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="px-8 py-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-500 shadow-inner group-hover:bg-blue-600 group-hover:text-white transition-all duration-500">
                                        <i class="fas fa-building text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-900 tracking-tight italic uppercase">{{ $proveedor->nombre }}</p>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Socio Comercial Activo</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-8 text-center text-sm font-black text-slate-600 italic">
                                <span class="inline-flex items-center gap-2 px-4 py-2 bg-slate-50 text-slate-500 rounded-xl border border-slate-100">
                                    <i class="fas fa-phone-alt text-[10px]"></i>
                                    {{ $proveedor->telefono ?: 'No Registrado' }}
                                </span>
                            </td>
                            <td class="px-8 py-8 text-right">
                                <div class="flex justify-end gap-3">
                                    <button onclick='openEditModal(@json($proveedor))' 
                                            class="p-2.5 h-11 w-11 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-400 hover:bg-green-50 hover:text-green-600 transition-all border border-transparent hover:border-green-100" title="Editar Perfil">
                                        <i class="fas fa-edit text-sm"></i>
                                    </button>
                                    <button onclick="openDeleteModal('{{ $proveedor->id }}', '{{ $proveedor->nombre }}')" 
                                            class="p-2.5 h-11 w-11 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-400 hover:bg-red-50 hover:text-red-600 transition-all border border-transparent hover:border-red-100" title="Dar de Baja">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                        @if ($proveedores->isEmpty())
                        <tr>
                            <td colspan="4" class="p-24 text-center">
                                <div class="flex flex-col items-center gap-4 opacity-20">
                                    <i class="fas fa-address-book text-6xl"></i>
                                    <p class="text-[11px] font-black uppercase tracking-widest italic">Directorio de socios vacío</p>
                                </div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            @if($proveedores->hasPages())
                <div class="px-8 py-6 border-t border-slate-50 bg-slate-50/30">
                    {{ $proveedores->links() }}
                </div>
            @endif
        </div>

        <!-- Modales Core Logic -->

        <!-- Modal Crear -->
        <div id="createModal" class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm z-[110] hidden items-center justify-center p-4">
            <div class="bg-white rounded-[3rem] p-12 w-full max-w-md shadow-[0_50px_100px_rgba(0,0,0,0.2)] relative animate-in zoom-in-95 duration-300">
                <button onclick="closeModal('createModal')" class="absolute top-10 right-10 w-12 h-12 rounded-2xl bg-slate-50 text-slate-400 hover:bg-red-50 hover:text-red-600 transition-colors flex items-center justify-center">
                    <i class="fas fa-times text-xl"></i>
                </button>
                
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-12 italic border-b border-slate-50 pb-6">Vincular Nuevo Socio</h3>
                
                <form action="{{ route('compras.proveedor.store') }}" method="POST" class="space-y-8">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic block text-left">Nombre de Empresa / Razón Social</label>
                        <div class="relative group">
                            <i class="fas fa-briefcase absolute left-6 top-1/2 -translate-y-1/2 text-slate-200 group-focus-within:text-blue-500 transition-colors"></i>
                            <input type="text" name="nombre" required placeholder="Ej: Distribuidora Central S.A.C." 
                                   class="w-full pl-14 pr-6 py-5 bg-slate-50 border-none rounded-3xl focus:ring-[10px] focus:ring-blue-500/5 text-slate-900 font-bold transition-all outline-none placeholder:text-slate-200">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic block text-left">Línea de Contacto (Teléfono)</label>
                        <div class="relative group">
                            <i class="fas fa-mobile-alt absolute left-6 top-1/2 -translate-y-1/2 text-slate-200 group-focus-within:text-blue-500 transition-colors"></i>
                            <input type="text" name="telefono" placeholder="+51 999 999 999" 
                                   class="w-full pl-14 pr-6 py-5 bg-slate-50 border-none rounded-3xl focus:ring-[10px] focus:ring-blue-500/5 text-slate-900 font-bold transition-all outline-none placeholder:text-slate-200">
                        </div>
                    </div>
                    <div class="pt-4 text-left">
                        <button type="submit" class="w-full py-6 bg-blue-600 hover:bg-slate-950 text-white rounded-[2.5rem] font-black uppercase tracking-[0.2em] text-xs shadow-2xl shadow-blue-100 transition-all active:scale-95">
                            Establecer Vínculo
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Editar -->
        <div id="editModal" class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm z-[110] hidden items-center justify-center p-4">
            <div class="bg-white rounded-[3rem] p-12 w-full max-w-md shadow-[0_50px_100px_rgba(0,0,0,0.2)] relative animate-in zoom-in-95 duration-300">
                <button onclick="closeModal('editModal')" class="absolute top-10 right-10 w-12 h-12 rounded-2xl bg-slate-50 text-slate-400 hover:bg-red-50 hover:text-red-600 transition-colors flex items-center justify-center">
                    <i class="fas fa-times text-xl"></i>
                </button>
                
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-12 italic border-b border-slate-50 pb-6">Modificar Registros de Socio</h3>
                
                <form id="editForm" method="POST" class="space-y-8">
                    @csrf @method('PUT')
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic block text-left">Nombre Actualizado</label>
                        <input type="text" name="nombre" id="editNombre" required 
                               class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-[10px] focus:ring-blue-500/5 text-slate-900 font-bold transition-all outline-none">
                    </div>
                    <div class="space-y-2 text-left">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic block text-left">Teléfono de Contacto</label>
                        <input type="text" name="telefono" id="editTelefono" 
                               class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-[10px] focus:ring-blue-500/5 text-slate-900 font-bold transition-all outline-none">
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full py-6 bg-slate-950 hover:bg-blue-600 text-white rounded-[2.5rem] font-black uppercase tracking-[0.2em] text-xs shadow-2xl transition-all active:scale-95">
                            Guardar Cambios Pro
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Eliminar -->
        <div id="deleteModal" class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm z-[110] hidden items-center justify-center p-4">
            <div class="bg-white rounded-[3rem] p-12 w-full max-w-md shadow-[0_50px_100px_rgba(0,0,0,0.2)] relative animate-in zoom-in-95 duration-300 text-center">
                <h3 class="text-xs font-black text-red-500 uppercase tracking-[0.3em] mb-12 italic border-b border-red-50 pb-6">Gestión de Bajas</h3>
                
                <div class="w-20 h-20 mx-auto mb-8 rounded-[2rem] bg-red-50 text-red-500 flex items-center justify-center text-3xl">
                    <i class="fas fa-user-slash"></i>
                </div>
                
                <h4 class="text-xl font-black text-slate-900 italic tracking-tighter">¿Finalizar relación comercial?</h4>
                <p class="text-slate-400 font-bold text-sm mt-3 px-8 leading-relaxed">
                    Se desvinculará a "<span id="deleteProveedorNombre" class="text-red-600 underline font-black">---</span>" del directorio oficial. Esta acción se registrará en auditoría.
                </p>
                
                <form id="deleteForm" method="POST" class="mt-12 flex gap-4">
                    @csrf @method('DELETE')
                    <button type="button" onclick="closeModal('deleteModal')" class="flex-1 py-5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-[2rem] font-black uppercase tracking-[0.2em] text-[10px] transition-all">
                        Mejor no
                    </button>
                    <button type="submit" class="flex-1 py-5 bg-red-600 hover:bg-red-700 text-white rounded-[2rem] font-black uppercase tracking-[0.2em] text-[10px] transition-all shadow-2xl shadow-red-100">
                         Sí, Confirmar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            const modal = document.getElementById(id);
            if(modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if(modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = 'auto';
            }
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
            openModal('deleteModal');

            form.onsubmit = function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Confirmar baja?',
                    text: `Se eliminará el proveedor "${nombre}" definitivamente.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ff0000',
                    cancelButtonColor: '#0f172a',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    customClass: { popup: 'rounded-[3rem]' }
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            };
        }

        // Close on clicks outside
        document.querySelectorAll('.fixed').forEach(modal => {
            modal.addEventListener('click', (e) => {
                if(e.target === modal) closeModal(modal.id);
            });
        });
    </script>
</x-app-layout>