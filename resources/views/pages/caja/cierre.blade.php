<x-app-layout>
  <div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Cierre de Caja</h2>

    @if ($cajasAbiertas->isEmpty())
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 p-4 mb-4 rounded-lg shadow-md">
      No hay cajas abiertas para cerrar.
    </div>
    @else
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 p-4 mb-4 rounded-lg shadow-md">
      {{ session('success') }}
    </div>
    @endif
    @if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 p-4 mb-4 rounded-lg shadow-md">
      {{ session('error') }}
    </div>
    @endif

    <div class="overflow-x-auto">
      <table class="w-full bg-white border-collapse border border-gray-300">
        <thead>
          <tr class="bg-gray-800 text-white">
            <th class="p-2 text-center border border-gray-300">N°</th>
            <th class="p-2 text-center border border-gray-300">Monto Apertura</th>
            <th class="p-2 text-center border border-gray-300">Fecha Apertura</th>
            <th class="p-2 text-center border border-gray-300">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($cajasAbiertas as $index => $caja)
          <tr class="hover:bg-gray-100">
            <td class="p-2 text-center border border-gray-300">{{ $index + 1 }}</td>
            <td class="p-2 text-center border border-gray-300">S/ {{ number_format($caja->monto_apertura, 2) }}</td>
            <td class="p-2 text-center border border-gray-300">{{ $caja->fecha_apertura->format('d/m/Y H:i') }}</td>
            <td class="p-2 text-center border border-gray-300">
              <form action="{{ route('caja.update', $caja->id) }}" method="POST" class="inline">
                @csrf
                @method('PUT')
                <button type="submit"
                  class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition duration-300">
                  <i class="fas fa-lock"></i> Cerrar Caja
                </button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif
  </div>
</x-app-layout>