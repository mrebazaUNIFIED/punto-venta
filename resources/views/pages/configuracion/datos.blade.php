<x-app-layout>
    <div class="max-w-4xl mx-auto px-6 py-10 bg-white rounded-xl shadow-md">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 border-b pb-3 text-center">Datos de la Empresa</h2>

      

        {{-- Logo centrado --}}
        <div class="flex justify-center mb-8">
            @if ($empresa->logo)
                <img src="{{ asset('storage/logos/' . $empresa->logo) }}"
                     alt="Logo de la empresa"
                     class="h-32 w-32 object-contain border-2 border-gray-300 bg-white rounded-full shadow-md">
            @else
                <div class="h-32 w-32 flex items-center justify-center bg-gray-100 rounded-full text-gray-400 border border-gray-300 text-sm">
                    Sin logo
                </div>
            @endif
        </div>

        <form method="POST" action="{{ route('configuracion.empresa.update', $empresa->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach([
                    'razon_social' => 'Razón Social',
                    'nombre_comercial' => 'Nombre Comercial',
                    'ruc' => 'RUC',
                    'direccion_fiscal' => 'Dirección Fiscal',
                    'ubigeo' => 'Ubigeo',
                    'departamento' => 'Departamento',
                    'provincia' => 'Provincia',
                    'distrito' => 'Distrito',
                    'telefono' => 'Teléfono',
                    'correo' => 'Correo Electrónico'
                ] as $campo => $etiqueta)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ $etiqueta }}</label>
                        <input type="{{ $campo === 'correo' ? 'email' : 'text' }}"
                               name="{{ $campo }}"
                               value="{{ old($campo, $empresa->$campo) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition">
                    </div>
                @endforeach

                {{-- Logo input --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Actualizar Logo</label>
                    <input type="file"
                           name="logo"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:outline-none">
                </div>
            </div>

            <div class="mt-8 text-right">
                <button type="submit"
                        class="inline-flex items-center px-6 py-3 bg-gray-800 hover:bg-gray-700 text-white font-semibold rounded-xl shadow-md transition duration-200">
                    <i class="fas fa-save mr-2"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
