<x-app-layout>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Apertura de Caja</h2>

        @if ($cajaAbierta)
        <div class="bg-red-100 border border-red-400 text-red-700 p-4 mb-4 rounded-lg shadow-md">
            Ya tienes una caja abierta. Monto de apertura: S/ {{ number_format($cajaAbierta->monto_apertura, 2) }}. Fecha: {{ $cajaAbierta->fecha_apertura->format('d/m/Y H:i') }}.
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

        <button id="openModalButton" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition duration-300">
            Abrir Caja
        </button>

        <!-- Modal -->
        <div id="aperturaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg w-1/3 max-h-[80vh] overflow-y-auto shadow-lg">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold mb-4">Nueva Apertura de Caja</h3>
                    <button id="closeModalButton" class=" text-gray-500 mb-3  rounded float-right"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <form action="{{ route('caja.apertura') }}" method="POST" class="mt-4">
                    @csrf
                    <div class="mb-4">
                        <label for="monto_apertura" class="block text-sm font-medium text-gray-700">Monto de Apertura (S/)</label>
                        <input type="number" name="monto_apertura" id="monto_apertura" step="0.01" class="border rounded p-2 w-full mt-1 focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition duration-300">Guardar</button>
                </form>
            </div>
        </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const openModalButton = document.getElementById('openModalButton');
            const aperturaModal = document.getElementById('aperturaModal');
            const closeModalButton = document.getElementById('closeModalButton');

            openModalButton.addEventListener('click', function() {
                aperturaModal.classList.remove('hidden');
            });

            closeModalButton.addEventListener('click', function() {
                aperturaModal.classList.add('hidden');
            });
        });
    </script>
</x-app-layout>