<x-app-layout>
    <div class="p-6">
        <!-- Mensajes de feedback -->
        @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('success') }}
        </div>
        @endif
        @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('error') }}
        </div>
        @endif

        <h2 class="text-3xl font-bold mb-6 text-gray-800">Gestión de Usuarios</h2>

        <!-- Botón Nuevo Usuario -->
        <div class="mb-6">
            <button onclick="openModal('crearUsuarioModal')" class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                <i class="fas fa-user-plus mr-2"></i> Nuevo Usuario
            </button>
        </div>

        <!-- Tabla de usuarios -->
        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table class="min-w-full text-sm text-left">
                <thead class="text-xs text-white uppercase bg-gray-900">
                    <tr class="text-center">
                        <th class="px-6 py-3">N°</th>
                        <th class="px-6 py-3">Nombre</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Rol(es)</th>
                        <th class="px-6 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($usuarios as $index => $usuario)
                    <tr class="hover:bg-gray-200 text-center">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">{{ $usuario->name }}</td>
                        <td class="px-6 py-4">{{ $usuario->email }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-block bg-blue-600 text-white font-bold text-xs px-2 py-1 rounded-md">
                                {{ $usuario->roles->pluck('name')->join(', ') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center space-x-2">
                            <button onclick="openModal('verUsuarioModal_{{ $usuario->id }}')" class="text-blue-600 hover:text-blue-800" title="Ver">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="openModal('editarUsuarioModal_{{ $usuario->id }}')" class="text-green-600 hover:text-green-800" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="openModal('cambiarPasswordModal_{{ $usuario->id }}')" class="text-yellow-500 hover:text-yellow-700" title="Contraseña">
                                <i class="fas fa-key"></i>
                            </button>
                            <button onclick="openModal('eliminarUsuarioModal_{{ $usuario->id }}')" class="text-red-600 hover:text-red-800" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No hay usuarios registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Modales por usuario -->
        @foreach($usuarios as $usuario)
        <!-- Ver Usuario -->
        <div id="verUsuarioModal_{{ $usuario->id }}" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded shadow-xl w-full max-w-md relative">
                <button onclick="closeModal('verUsuarioModal_{{ $usuario->id }}')" class="modal-close absolute top-2 right-2 text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times"></i>
                </button>
                <h2 class="text-xl font-semibold mb-4">Ver Usuario</h2>
                <hr class="mb-3">
                <p><strong>Nombre:</strong> {{ $usuario->name }}</p>
                <p><strong>Email:</strong> {{ $usuario->email }}</p>
                <p><strong>Rol(es):</strong> {{ $usuario->roles->pluck('name')->join(', ') }}</p>
            </div>
        </div>

        <!-- Editar Usuario -->
        <div id="editarUsuarioModal_{{ $usuario->id }}" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded shadow-xl w-full max-w-md relative">
                <button onclick="closeModal('editarUsuarioModal_{{ $usuario->id }}')" class="modal-close absolute top-2 right-2 text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times"></i>
                </button>
                <h2 class="text-xl font-semibold mb-4">Editar Usuario</h2>
                <hr class="mb-3">
                <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <input name="name" value="{{ old('name', $usuario->name) }}" class="w-full border p-2 rounded" placeholder="Nombre">
                            @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <input name="email" value="{{ old('email', $usuario->email) }}" class="w-full border p-2 rounded" placeholder="Email">
                            @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block font-medium">Rol:</label>
                            <select name="role" class="w-full border p-2 rounded">
                                @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ $usuario->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-4 text-right">
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Cambiar Contraseña -->
        <div id=" plaguesModal_{{ $usuario->id }}" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded shadow-xl w-full max-w-md relative">
                <button onclick="closeModal('cambiarPasswordModal_{{ $usuario->id }}')" class="modal-close absolute top-2 right-2 text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times"></i>
                </button>
                <h2 class="text-xl font-semibold mb-4">Cambiar Contraseña</h2>
                <hr class="mb-3">
                <form action="{{ route('usuarios.password', $usuario->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <input type="password" name="password" class="w-full border p-2 rounded" placeholder="Nueva contraseña">
                            @error('password')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <input type="password" name="password_confirmation" class="w-full border p-2 rounded" placeholder="Confirmar contraseña">
                            @error('password_confirmation')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="text-right mt-4">
                        <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Eliminar Usuario -->
        <div id="eliminarUsuarioModal_{{ $usuario->id }}" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded shadow-xl w-full max-w-md relative">
                <button onclick="closeModal('eliminarUsuarioModal_{{ $usuario->id }}')" class="modal-close absolute top-2 right-2 text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times"></i>
                </button>
                <h2 class="text-xl font-semibold mb-4">Eliminar Usuario</h2>
                <hr class="mb-3">
                <p>¿Estás seguro de que deseas eliminar a <strong>{{ $usuario->name }}</strong>?</p>
                <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" class="mt-4 text-right">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Eliminar</button>
                </form>
            </div>
        </div>
        @endforeach

        <!-- Modal Crear Usuario -->
        <div id="crearUsuarioModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded shadow-xl w-full max-w-md relative">
                <button onclick="closeModal('crearUsuarioModal')" class="modal-close absolute top-2 right-2 text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times"></i>
                </button>
                <h2 class="text-xl font-semibold mb-4">Crear Usuario</h2>
                <hr class="mb-3">
                <form action="{{ route('usuarios.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <input name="name" class="w-full border p-2 rounded" placeholder="Nombre" value="{{ old('name') }}">
                            @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <input name="email" class="w-full border p-2 rounded" placeholder="Email" value="{{ old('email') }}">
                            @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <input name="password" type="password" class="w-full border p-2 rounded" placeholder="Contraseña">
                            @error('password')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <input name="password_confirmation" type="password" class="w-full border p-2 rounded" placeholder="Confirmar contraseña">
                            @error('password_confirmation')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block font-medium">Rol:</label>
                            <select name="role" class="w-full border p-2 rounded">
                                @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-4 text-right">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Crear</button>
                    </div>
                </form>
            </div>
        </div>

        <style>
            .modal-close {
                font-size: 1.5rem;
                color: #6b7280;
                cursor: pointer;
                transition: color 0.2s;
            }

            .modal-close:hover {
                color: #111827;
            }
        </style>

        <script>
            function openModal(id) {
                document.getElementById(id).classList.remove('hidden');
            }

            function closeModal(id) {
                document.getElementById(id).classList.add('hidden');
            }
        </script>
    </div>
</x-app-layout>