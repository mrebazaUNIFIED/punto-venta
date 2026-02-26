<aside x-bind:class="{ 'w-64': open, 'w-16': !open }" class="bg-gray-900 text-white p-4 transition-all duration-300 ease-in-out h-screen overflow-y-auto">
    <div class="mb-6 flex justify-center">
        <img
            src="{{ asset('images/logo.png') }}"
            alt="Logo"
            x-bind:class="{ 'h-20': open, 'h-10': !open }"
            class="w-auto transition-all duration-300 ease-in-out mx-auto" />
    </div>
    <nav>
        <ul>
            <li class="mb-2">
                <button x-on:click.prevent="submenuOpen1 = !submenuOpen1" x-bind:class="{ 'flex items-center justify-between p-2 rounded hover:bg-gray-700 w-full': open, 'flex items-center justify-center p-2 rounded hover:bg-gray-700 w-full': !open }" class="hover:bg-gray-700 p-2 block rounded focus:outline-none">
                    <div class="flex items-center">
                        <i class="fas fa-tachometer-alt pr-2" x-show="open || !open"></i>
                        <span x-show="open">Panel de control</span>
                    </div>
                    <i class="fas fa-chevron-down text-sm" x-show="open"></i>
                </button>
                <ul x-show="submenuOpen1" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="ml-4 mt-1 space-y-1">
                    <li>
                        <a href="{{ route('panel.usuarios.index') }}" class="flex items-center p-2 rounded hover:bg-gray-700 text-sm" x-show="open">
                            <div class="w-5 h-5 bg-gray-500 rounded-full flex items-center justify-center mr-2"><i class="fas fa-users text-white text-xs"></i></div>Usuarios
                        </a>
                    </li>

                </ul>
            </li>
            <li class="mb-2">
                <button x-on:click.prevent="submenuOpen2 = !submenuOpen2" x-bind:class="{ 'flex items-center justify-between p-2 rounded hover:bg-gray-700 w-full': open, 'flex items-center justify-center p-2 rounded hover:bg-gray-700 w-full': !open }" class="hover:bg-gray-700 p-2 block rounded focus:outline-none">
                    <div class="flex items-center">
                        <i class="fas fa-cash-register pr-2" x-show="open || !open"></i>
                        <span x-show="open">Caja</span>
                    </div>
                    <i class="fas fa-chevron-down text-sm" x-show="open"></i>
                </button>
                <ul x-show="submenuOpen2" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="ml-4 mt-1 space-y-1">
                    <li><a href="{{ route('caja.apertura') }}" class="flex items-center p-2 rounded hover:bg-gray-700 text-sm" x-show="open">
                            <div class="w-5 h-5 bg-gray-500 rounded-full flex items-center justify-center mr-2"><i class="fas fa-sign-in-alt text-white text-xs"></i></div>Apertura de caja
                        </a></li>
                    <li><a href="{{ route('caja.cierre') }}" class="flex items-center p-2 rounded hover:bg-gray-700 text-sm" x-show="open">
                            <div class="w-5 h-5 bg-gray-500 rounded-full flex items-center justify-center mr-2"><i class="fas fa-user-slash text-white text-xs"></i></div>Cierre de caja
                        </a></li>
                    <li><a href="{{ route('caja.historico') }}" class="flex items-center p-2 rounded hover:bg-gray-700 text-sm" x-show="open">
                            <div class="w-5 h-5 bg-gray-500 rounded-full flex items-center justify-center mr-2"><i class="fas fa-history text-white text-xs"></i></div>Histórico de caja
                        </a></li>
                </ul>
            </li>
            <li class="mb-2">
                <button x-on:click.prevent="submenuOpen3 = !submenuOpen3" x-bind:class="{ 'flex items-center justify-between p-2 rounded hover:bg-gray-700 w-full': open, 'flex items-center justify-center p-2 rounded hover:bg-gray-700 w-full': !open }" class="hover:bg-gray-700 p-2 block rounded focus:outline-none">
                    <div class="flex items-center">
                        <i class="fas fa-warehouse pr-2" x-show="open || !open"></i>
                        <span x-show="open">Almacén</span>
                    </div>
                    <i class="fas fa-chevron-down text-sm" x-show="open"></i>
                </button>
                <ul x-show="submenuOpen3" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="ml-4 mt-1 space-y-1">
                    <li><a href="{{ route('almacen.categoria.index') }}" class="flex items-center p-2 rounded hover:bg-gray-700 text-sm" x-show="open">
                            <div class="w-5 h-5 bg-gray-500 rounded-full flex items-center justify-center mr-2"><i class="fas fa-list text-white text-xs"></i></div>Categorías
                        </a></li>
                    <li><a href="{{ route('almacen.articulo.index') }}" class="flex items-center p-2 rounded hover:bg-gray-700 text-sm" x-show="open">
                            <div class="w-5 h-5 bg-gray-500 rounded-full flex items-center justify-center mr-2"><i class="fas fa-box-open text-white text-xs"></i></div>Artículos
                        </a></li>


                    <li><a href="{{ route('almacen.inventario.index') }}" class="flex items-center p-2 rounded hover:bg-gray-700 text-sm" x-show="open">
                            <div class="w-5 h-5 bg-gray-500 rounded-full flex items-center justify-center mr-2"><i class="fas fa-warehouse text-white text-xs"></i></div>Inventario
                        </a></li>
                </ul>
            </li>
            <li class="mb-2">
                <button x-on:click.prevent="submenuOpen4 = !submenuOpen4" x-bind:class="{ 'flex items-center justify-between p-2 rounded hover:bg-gray-700 w-full': open, 'flex items-center justify-center p-2 rounded hover:bg-gray-700 w-full': !open }" class="hover:bg-gray-700 p-2 block rounded focus:outline-none">
                    <div class="flex items-center">
                        <i class="fas fa-shopping-cart pr-2" x-show="open || !open"></i>
                        <span x-show="open">Compras</span>
                    </div>
                    <i class="fas fa-chevron-down text-sm" x-show="open"></i>
                </button>
                <ul x-show="submenuOpen4" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="ml-4 mt-1 space-y-1">
                    <li><a href="{{ route('compras.entrada.index') }}" class="flex items-center p-2 rounded hover:bg-gray-700 text-sm" x-show="open">
                            <div class="w-5 h-5 bg-gray-500 rounded-full flex items-center justify-center mr-2"><i class="fas fa-sign-in-alt text-white text-xs"></i></div>Entrada de productos
                        </a></li>
                    <li><a href="{{ route('compras.detalle.index') }}" class="flex items-center p-2 rounded hover:bg-gray-700 text-sm" x-show="open">
                            <div class="w-5 h-5 bg-gray-500 rounded-full flex items-center justify-center mr-2"><i class="fas fa-list-ul text-white text-xs"></i></div>Detalle entradas
                        </a></li>
                    <li><a href="{{ route('compras.proveedor.index') }}" class="flex items-center p-2 rounded hover:bg-gray-700 text-sm" x-show="open">
                            <div class="w-5 h-5 bg-gray-500 rounded-full flex items-center justify-center mr-2"><i class="fas fa-truck text-white text-xs"></i></div>Proveedores
                        </a></li>
                </ul>
            </li>
            <li class="mb-2">
                <button x-on:click.prevent="submenuOpen5 = !submenuOpen5" x-bind:class="{ 'flex items-center justify-between p-2 rounded hover:bg-gray-700 w-full': open, 'flex items-center justify-center p-2 rounded hover:bg-gray-700 w-full': !open }" class="hover:bg-gray-700 p-2 block rounded focus:outline-none">
                    <div class="flex items-center">
                        <i class="fas fa-chart-line pr-2" x-show="open || !open"></i>
                        <span x-show="open">Ventas</span>
                    </div>
                    <i class="fas fa-chevron-down text-sm" x-show="open"></i>
                </button>
                <ul x-show="submenuOpen5" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="ml-4 mt-1 space-y-1">
                    <li><a href="{{ route('ventas.posventa.index') }}" class="flex items-center p-2 rounded hover:bg-gray-700 text-sm" x-show="open">
                            <div class="w-5 h-5 bg-gray-500 rounded-full flex items-center justify-center mr-2"><i class="fas fa-shopping-bag text-white text-xs"></i></div>PosVenta
                        </a></li>
                    <li><a href="{{ route('ventas.detalle.index') }}" class="flex items-center p-2 rounded hover:bg-gray-700 text-sm" x-show="open">
                            <div class="w-5 h-5 bg-gray-500 rounded-full flex items-center justify-center mr-2"><i class="fas fa-info-circle text-white text-xs"></i></div>Detalles Venta
                        </a></li>
                </ul>
            </li>
            <li class="mb-2">
                <button x-on:click.prevent="submenuOpen6 = !submenuOpen6" x-bind:class="{ 'flex items-center justify-between p-2 rounded hover:bg-gray-700 w-full': open, 'flex items-center justify-center p-2 rounded hover:bg-gray-700 w-full': !open }" class="hover:bg-gray-700 p-2 block rounded focus:outline-none">
                    <div class="flex items-center">
                        <i class="fas fa-file-alt pr-2" x-show="open || !open"></i>
                        <span x-show="open">Reportes</span>
                    </div>
                    <i class="fas fa-chevron-down text-sm" x-show="open"></i>
                </button>
                <ul x-show="submenuOpen6" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="ml-4 mt-1 space-y-1">
                    <li><a href="{{ route('empresa.reportes') }}" class="flex items-center p-2 rounded hover:bg-gray-700 text-sm" x-show="open">
                            <div class="w-5 h-5 bg-gray-500 rounded-full flex items-center justify-center mr-2"><i class="fas fa-chart-bar text-white text-xs"></i></div>Reportes gráficos
                        </a></li>
                </ul>
            </li>
            <li class="mb-2">
                <button x-on:click.prevent="submenuOpen7 = !submenuOpen7" x-bind:class="{ 'flex items-center justify-between p-2 rounded hover:bg-gray-700 w-full': open, 'flex items-center justify-center p-2 rounded hover:bg-gray-700 w-full': !open }" class="hover:bg-gray-700 p-2 block rounded focus:outline-none">
                    <div class="flex items-center">
                        <i class="fas fa-cog pr-2" x-show="open || !open"></i>
                        <span x-show="open">Configuración</span>
                    </div>
                    <i class="fas fa-chevron-down text-sm" x-show="open"></i>
                </button>
                <ul x-show="submenuOpen7" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="ml-4 mt-1 space-y-1">
                    <li><a href="{{ route('configuracion.empresa.index') }}" class="flex items-center p-2 rounded hover:bg-gray-700 text-sm" x-show="open">
                            <div class="w-5 h-5 bg-gray-500 rounded-full flex items-center justify-center mr-2"><i class="fas fa-building text-white text-xs"></i></div>Datos de la empresa
                        </a></li>
                </ul>
            </li>
        </ul>
    </nav>
</aside>