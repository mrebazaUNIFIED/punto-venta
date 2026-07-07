<x-app-layout>
    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-4xl font-black text-slate-950 tracking-tighter">Gestión de Personal</h2>
                <p class="text-slate-400 font-medium mt-1">Administra los accesos y roles de tu equipo de trabajo.</p>
            </div>
            <button onclick="openModal('crearUsuarioModal')"
                class="group flex items-center justify-center gap-3 px-8 py-4 bg-blue-600 hover:bg-slate-950 text-white rounded-[2rem] font-black uppercase tracking-[0.2em] text-xs shadow-2xl shadow-blue-200 transition-all active:scale-95">
                <i class="fas fa-user-plus text-lg group-hover:rotate-12 transition-transform"></i>
                Nuevo Usuario
            </button>
        </div>

        <!-- Alert Section -->
        @if (session('success') || session('error'))
            <div class="animate-in fade-in zoom-in-95 duration-500">
                @if (session('success'))
                    <div class="flex items-center gap-4 p-5 bg-green-50 border border-green-100 rounded-3xl text-green-700">
                        <div class="w-10 h-10 rounded-2xl bg-green-100 flex items-center justify-center shrink-0">
                            <i class="fas fa-check-circle text-lg"></i>
                        </div>
                        <p class="font-bold text-sm italic">{{ session('success') }}</p>
                    </div>
                @endif
                @if (session('error'))
                    <div class="flex items-center gap-4 p-5 bg-red-50 border border-red-100 rounded-3xl text-red-700">
                        <div class="w-10 h-10 rounded-2xl bg-red-100 flex items-center justify-center shrink-0">
                            <i class="fas fa-exclamation-circle text-lg"></i>
                        </div>
                        <p class="font-bold text-sm italic">{{ session('error') }}</p>
                    </div>
                @endif
            </div>
        @endif

        <!-- Users Table Card -->
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
                                Colaborador</th>
                            <th
                                class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic">
                                Identidad Digital</th>
                            <th
                                class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic text-center">
                                Nivel de Acceso</th>
                            <th
                                class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic text-right">
                                Acciones Pro</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($usuarios as $index => $usuario)
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-6">
                                    <span
                                        class="text-xs font-black text-slate-300">#{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center border border-white shadow-sm ring-4 ring-slate-50 group-hover:ring-brand transition-all">
                                            <span
                                                class="text-slate-500 font-black text-lg">{{ substr($usuario->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-slate-900 tracking-tight">{{ $usuario->name }}
                                            </p>
                                            <p
                                                class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                                                Activo en Sistema</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-2 text-slate-500">
                                        <i class="fas fa-envelope text-[10px] opacity-30"></i>
                                        <span class="text-xs font-bold">{{ $usuario->email }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    @php
                                        $rolesArr = $usuario->roles->pluck('name')->toArray();
                                        $isAdmin = in_array('admin', $rolesArr);
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[0.2em] {{ $isAdmin ? 'bg-blue-50 text-brand border border-blue-100' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                        {{ $usuario->roles->pluck('name')->join(' / ') }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-2 outline-none">
                                        <button onclick="openModal('verUsuarioModal_{{ $usuario->id }}')"
                                            class="p-2.5 h-10 w-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:bg-blue-50 hover:text-brand transition-all"
                                            title="Ver Detalle">
                                            <i class="fas fa-eye text-sm"></i>
                                        </button>
                                        <button onclick="openModal('editarUsuarioModal_{{ $usuario->id }}')"
                                            class="p-2.5 h-10 w-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:bg-green-50 hover:text-green-600 transition-all"
                                            title="Editar">
                                            <i class="fas fa-edit text-sm"></i>
                                        </button>
                                        <button onclick="openModal('cambiarPasswordModal_{{ $usuario->id }}')"
                                            class="p-2.5 h-10 w-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:bg-yellow-50 hover:text-yellow-600 transition-all"
                                            title="Seguridad">
                                            <i class="fas fa-key text-sm"></i>
                                        </button>
                                        <button onclick="openModal('eliminarUsuarioModal_{{ $usuario->id }}')"
                                            class="p-2.5 h-10 w-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:bg-red-50 hover:text-red-600 transition-all"
                                            title="Eliminar">
                                            <i class="fas fa-trash-alt text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center gap-4 opacity-20">
                                        <i class="fas fa-users text-6xl"></i>
                                        <p class="text-sm font-black uppercase tracking-widest italic">No se encontraron
                                            colaboradores</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modales Core Logic -->
        @foreach($usuarios as $usuario)
            <!-- Modal Ver -->
            <div id="verUsuarioModal_{{ $usuario->id }}"
                class="hidden fixed inset-0 bg-slate-950/40 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
                <div
                    class="bg-white rounded-[3rem] p-10 w-full max-w-lg shadow-[0_50px_100px_rgba(0,0,0,0.15)] relative animate-in zoom-in-95 duration-300">
                    <button onclick="closeModal('verUsuarioModal_{{ $usuario->id }}')"
                        class="absolute top-8 right-8 w-10 h-10 rounded-2xl bg-slate-50 text-slate-400 hover:bg-red-50 hover:text-red-600 transition-colors flex items-center justify-center">
                        <i class="fas fa-times text-lg"></i>
                    </button>

                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-10 italic">Perfil de Usuario
                    </h3>

                    <div class="flex flex-col items-center text-center space-y-6">
                        <div
                            class="w-32 h-32 rounded-[2.5rem] bg-brand flex items-center justify-center text-white text-5xl font-black shadow-2xl shadow-blue-200">
                            {{ substr($usuario->name, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="text-2xl font-black text-slate-900">{{ $usuario->name }}</h4>
                            <p class="text-slate-500 font-bold italic">{{ $usuario->email }}</p>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-4 pt-6">
                            <div class="bg-slate-50 p-6 rounded-3xl text-left">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Rol de Acceso
                                </p>
                                <p class="text-sm font-black text-brand uppercase">
                                    {{ $usuario->roles->pluck('name')->join(', ') }}</p>
                            </div>
                            <div class="bg-slate-50 p-6 rounded-3xl text-left">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Estado Cuenta
                                </p>
                                <p class="text-sm font-black text-green-600 uppercase">Activo</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Editar -->
            <div id="editarUsuarioModal_{{ $usuario->id }}"
                class="hidden fixed inset-0 bg-slate-950/40 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
                <div
                    class="bg-white rounded-[3rem] p-10 w-full max-w-lg shadow-[0_50px_100px_rgba(0,0,0,0.15)] relative animate-in zoom-in-95 duration-300">
                    <button onclick="closeModal('editarUsuarioModal_{{ $usuario->id }}')"
                        class="absolute top-8 right-8 w-10 h-10 rounded-2xl bg-slate-50 text-slate-400 hover:bg-red-50 hover:text-red-600 transition-colors flex items-center justify-center">
                        <i class="fas fa-times text-lg"></i>
                    </button>

                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-10 italic">Actualizar Datos
                    </h3>

                    <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Nombre
                                Completo</label>
                            <input name="name" value="{{ old('name', $usuario->name) }}"
                                class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-[8px] focus:ring-brand/5 text-slate-900 font-bold transition-all outline-none">
                        </div>
                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Correo
                                Electrónico</label>
                            <input name="email" value="{{ old('email', $usuario->email) }}"
                                class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-[8px] focus:ring-brand/5 text-slate-900 font-bold transition-all outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Nivel
                                de Acceso</label>
                            <select name="role"
                                class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-[8px] focus:ring-brand/5 text-slate-900 font-bold transition-all outline-none appearance-none">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ $usuario->hasRole($role->name) ? 'selected' : '' }}>
                                        {{ strtoupper($role->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="pt-6">
                            <button type="submit"
                                class="w-full py-5 bg-brand hover:bg-brand-hover text-white rounded-[2rem] font-black uppercase tracking-[0.2em] text-xs shadow-2xl shadow-blue-100 transition-all active:scale-95">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Password -->
            <div id="cambiarPasswordModal_{{ $usuario->id }}"
                class="hidden fixed inset-0 bg-slate-950/40 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
                <div
                    class="bg-white rounded-[3rem] p-10 w-full max-w-lg shadow-[0_50px_100px_rgba(0,0,0,0.15)] relative animate-in zoom-in-95 duration-300">
                    <button onclick="closeModal('cambiarPasswordModal_{{ $usuario->id }}')"
                        class="absolute top-8 right-8 w-10 h-10 rounded-2xl bg-slate-50 text-slate-400 hover:bg-red-50 hover:text-red-600 transition-colors flex items-center justify-center">
                        <i class="fas fa-times text-lg"></i>
                    </button>

                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-10 italic">Seguridad de
                        Cuenta</h3>

                    <form action="{{ route('usuarios.password', $usuario->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Nueva
                                Contraseña</label>
                            <input type="password" name="password"
                                class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-[8px] focus:ring-brand/5 text-slate-900 font-bold transition-all outline-none">
                        </div>
                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Confirmar
                                Nueva Contraseña</label>
                            <input type="password" name="password_confirmation"
                                class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-[8px] focus:ring-brand/5 text-slate-900 font-bold transition-all outline-none">
                        </div>
                        <div class="pt-6">
                            <button type="submit"
                                class="w-full py-5 bg-yellow-500 hover:bg-slate-950 text-white rounded-[2rem] font-black uppercase tracking-[0.2em] text-xs shadow-2xl shadow-yellow-100 transition-all active:scale-95">
                                Actualizar Credenciales
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Eliminar -->
            <div id="eliminarUsuarioModal_{{ $usuario->id }}"
                class="hidden fixed inset-0 bg-slate-950/40 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
                <div
                    class="bg-white rounded-[3rem] p-10 w-full max-w-lg shadow-[0_50px_100px_rgba(0,0,0,0.15)] relative animate-in zoom-in-95 duration-300">
                    <h3 class="text-xs font-black text-red-500 uppercase tracking-[0.3em] mb-10 italic">Zona de Riesgo</h3>

                    <div class="text-center space-y-6">
                        <div
                            class="w-20 h-20 mx-auto rounded-[2rem] bg-red-50 text-red-500 flex items-center justify-center text-3xl">
                            <i class="fas fa-user-minus"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-black text-slate-900 italic">¿Eliminar acceso definitivo?</h4>
                            <p class="text-slate-400 font-bold text-sm mt-2 leading-relaxed">
                                Estás a punto de revocar el acceso de <span
                                    class="text-slate-900 underline">{{ $usuario->name }}</span>. Esta acción es
                                irreversible.
                            </p>
                        </div>

                        <div class="flex gap-4 pt-6">
                            <button onclick="closeModal('eliminarUsuarioModal_{{ $usuario->id }}')"
                                class="flex-1 py-5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-[2rem] font-black uppercase tracking-[0.2em] text-[10px] transition-all">
                                Cancelar
                            </button>
                            <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full py-5 bg-red-600 hover:bg-red-700 text-white rounded-[2rem] font-black uppercase tracking-[0.2em] text-[10px] transition-all shadow-2xl shadow-red-100">
                                    Sí, Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Modal Crear Global -->
        <div id="crearUsuarioModal"
            class="hidden fixed inset-0 bg-slate-950/40 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
            <div
                class="bg-white rounded-[3rem] p-10 w-full max-w-lg shadow-[0_50px_100px_rgba(0,0,0,0.15)] relative animate-in zoom-in-95 duration-300 overflow-y-auto max-h-[90vh]">
                <button onclick="closeModal('crearUsuarioModal')"
                    class="absolute top-8 right-8 w-10 h-10 rounded-2xl bg-slate-50 text-slate-400 hover:bg-red-50 hover:text-red-600 transition-colors flex items-center justify-center">
                    <i class="fas fa-times text-lg"></i>
                </button>

                <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-10 italic">Nuevo Colaborador
                </h3>

                <form action="{{ route('usuarios.store') }}" method="POST" class="space-y-6 text-left">
                    @csrf
                    <div class="space-y-2 text-left">
                        <label
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic text-left block">Nombre
                            Completo</label>
                        <input name="name" value="{{ old('name') }}" placeholder="Ej: Juan Pérez"
                            class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-[8px] focus:ring-brand/5 text-slate-900 font-bold transition-all outline-none">
                    </div>
                    <div class="space-y-2 text-left text-left">
                        <label
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic text-left block">Correo
                            Electrónico</label>
                        <input name="email" value="{{ old('email') }}" placeholder="juan@negocio.com"
                            class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-[8px] focus:ring-brand/5 text-slate-900 font-bold transition-all outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic block text-left">Contraseña</label>
                            <input type="password" name="password"
                                class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-[8px] focus:ring-brand/5 text-slate-900 font-bold transition-all outline-none">
                        </div>
                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic block text-left">Confirmar</label>
                            <input type="password" name="password_confirmation"
                                class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-[8px] focus:ring-brand/5 text-slate-900 font-bold transition-all outline-none">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic block text-left">Nivel
                            de Acceso</label>
                        <select name="role"
                            class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-[8px] focus:ring-brand/5 text-slate-900 font-bold transition-all outline-none appearance-none">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ strtoupper($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pt-6">
                        <button type="submit"
                            class="w-full py-5 bg-brand hover:bg-brand-hover text-white rounded-[2rem] font-black uppercase tracking-[0.2em] text-xs shadow-2xl shadow-blue-100 transition-all active:scale-95">
                            Crear Perfil Pro
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        // Cerrar con ESC
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                const modals = document.querySelectorAll('[id*="Modal"]');
                modals.forEach(m => closeModal(m.id));
            }
        });
    </script>
</x-app-layout>