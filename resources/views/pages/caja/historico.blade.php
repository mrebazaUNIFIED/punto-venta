<x-app-layout>
  <div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Histórico de Cajas</h2>

    <!-- Filtros por fechas -->
    <div class="mb-4 flex space-x-4">
      <div>
        <label for="start_date" class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
        <input type="date" id="start_date" name="start_date" class="border rounded p-2"
          value="{{ old('start_date', $startDate ?? now()->subDays(30)->format('Y-m-d')) }}">
      </div>
      <div>
        <label for="end_date" class="block text-sm font-medium text-gray-700">Fecha Fin</label>
        <input type="date" id="end_date" name="end_date" class="border rounded p-2"
          value="{{ old('end_date', $endDate ?? now()->format('Y-m-d')) }}">
      </div>
      <button id="filterButton"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition duration-300 mt-6">Filtrar</button>
    </div>

    @if ($historico->isEmpty())
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 p-4 mb-4 rounded-lg shadow-md">
      No hay historial de cajas disponible.
    </div>
    @else
    <div class="overflow-x-auto">
      <table class="w-full bg-white border-collapse border border-gray-300">
        <thead>
          <tr class="bg-gray-800 text-white">
            <th class="p-2 text-center border border-gray-300">N°</th>
            <th class="p-2 text-center border border-gray-300">Usuario</th>
            <th class="p-2 text-center border border-gray-300">Fecha Apertura</th>

            <th class="p-2 text-center border border-gray-300">Estado</th>

            <th class="p-2 text-center border border-gray-300">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($historico as $index => $caja)
          <tr class="hover:bg-gray-100">
            <td class="p-2 text-center border border-gray-300">{{ $index + 1 }}</td>
            <td class="p-2 text-center border border-gray-300">{{ $caja['usuario_nombre'] }}</td>
            <td class="p-2 text-center border border-gray-300">
              {{ \Carbon\Carbon::parse($caja['fecha_apertura'])->format('d/m/Y H:i') }}
            </td>
            <td
              class="p-2 text-center border border-gray-300 {{ $caja['estado']  === 'Cerrada' ? 'bg-green-500 text-white' : 'bg-blue-500 text-white' }}">
              <p>
                {{ $caja['estado'] }}
              </p>
            </td>
            <td class="p-2 text-center border border-gray-300">
              <button
                class="bg-gray-500 text-white px-3 py-1 rounded-lg hover:bg-gray-600 transition duration-300 view-detail"
                data-id="{{ $caja['id'] }}">
                <i class="fas fa-eye"></i>
              </button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif

    <!-- Modal para Detalle de Caja -->
    <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center">
      <div class="bg-white p-6 rounded-lg w-1/3 max-h-[80vh] overflow-y-auto shadow-lg">
        <h3 class="text-xl font-bold mb-2">Detalle de Caja</h3>
        <div id="usuarioNombre" class="bg-gray-200 p-2 text-center font-semibold text-lg w-full mb-4"></div>
        <table class="w-full bg-white border-collapse border border-gray-300">
          <thead>
            <tr class="bg-gray-800 text-white">
              <th class="p-2 text-left border border-gray-300">Descripción</th>
              <th class="p-2 text-right border border-gray-300">Valor</th>
            </tr>
          </thead>
          <tbody id="modalContent">
            <!-- Contenido del modal se cargará dinámicamente -->
          </tbody>
        </table>
        <button id="closeModalButton" class="bg-red-500 text-white p-2 rounded mt-4 float-right">Cerrar</button>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('filterButton').addEventListener('click', function() {
      const startDate = document.getElementById('start_date').value;
      const endDate = document.getElementById('end_date').value;
      window.location.href = `/caja/historico?start_date=${startDate}&end_date=${endDate}`;
    });

    document.addEventListener('DOMContentLoaded', function() {
      const detailModal = document.getElementById('detailModal');
      const closeModalButton = document.getElementById('closeModalButton');
      const modalContent = document.getElementById('modalContent');
      const usuarioNombre = document.getElementById('usuarioNombre');

      document.querySelectorAll('.view-detail').forEach(button => {
        button.addEventListener('click', function() {
          const cajaId = this.getAttribute('data-id');
          fetch(`/caja/${cajaId}`)
            .then(response => {
              if (!response.ok) {
                throw new Error('Respuesta no válida: ' + response.status);
              }
              return response.json();
            })
            .then(data => {
              usuarioNombre.textContent = data.usuario_nombre;
              const faltante = data.monto_cierre && (data.monto_cierre < data.monto_apertura) ? data
                .monto_apertura - data.monto_cierre : 0;
              const sobrante = data.monto_cierre && (data.monto_cierre > data.monto_apertura) ? data
                .monto_cierre - data.monto_apertura : 0;
              const ventasEfectivo = data.monto_cierre ? data.monto_cierre - data.monto_apertura : 0;

              modalContent.innerHTML = `
								<tr>
									<td class="p-2 border border-gray-300">Fecha de Operación</td>
									<td class="p-2 border border-gray-300 text-right">${new Date(data.fecha_apertura).toLocaleString()}</td>
								</tr>
								<tr>
									<td class="p-2 border border-gray-300">Fondo de Caja</td>
									<td class="p-2 border border-gray-300 text-right">S/ ${number_format(data.monto_apertura, 2)}</td>
								</tr>
								<tr>
									<td class="p-2 border border-gray-300">Efectivo en Caja</td>
									<td class="p-2 border border-gray-300 text-right">S/ ${data.monto_cierre ? number_format(data.monto_cierre, 2) : '0.00'}</td>
								</tr>
								<tr>
	<td class="p-2 border border-gray-300">Ventas en Efectivo</td>
	<td class="p-2 border border-gray-300 text-right">S/ ${number_format(data.ventas_efectivo, 2)}</td>
</tr>
<tr>
	<td class="p-2 border border-gray-300">Ventas por Yape</td>
	<td class="p-2 border border-gray-300 text-right">S/ ${number_format(data.ventas_yape, 2)}</td>
</tr>
	<tr>
		<td class="p-2 border border-gray-300">Faltante</td>
		<td class="p-2 border border-gray-300 text-right ${faltante > 0 ? 'text-red-600 font-bold' : ''}">S/ ${number_format(faltante, 2)}</td>
	</tr>
	<tr>
		<td class="p-2 border border-gray-300">Sobrante</td>
		<td class="p-2 border border-gray-300 text-right ${sobrante > 0 ? 'text-green-600 font-bold' : ''}">S/ ${number_format(sobrante, 2)}</td>
	</tr>
	`;
              detailModal.classList.remove('hidden');
            })
            .catch(error => console.error('Error al cargar el detalle:', error));
        });
      });

      closeModalButton.addEventListener('click', function() {
        detailModal.classList.add('hidden');
      });
    });

    // Función number_format simulada para JavaScript
    function number_format(number, decimals) {
      return number.toFixed(decimals).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }
  </script>
</x-app-layout>