<aside x-bind:class="{ 'w-72': open, 'w-20': !open }"
  class="relative glass-sidebar border-r border-white/5 text-slate-400 p-4 transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] h-screen overflow-y-auto flex flex-col z-50 group">

  <!-- Sidebar Header (Logo) -->
  <div class="mb-10 px-2 flex items-center justify-center h-16">
    <div x-show="open" x-transition:enter="duration-300 transition-all"
      x-transition:enter-start="opacity-0 -translate-x-4" class="flex items-center gap-3">

      <span class="text-lg font-black text-white tracking-widest uppercase">Ferretería <span
          class="text-brand">Baylon</span></span>
    </div>
    <div x-show="!open" class="p-2 bg-brand/10 rounded-lg border border-brand/20">
      <x-application-logo class="w-6 h-6 fill-current text-brand" />
    </div>
  </div>

  <!-- Navigation Area -->
  <nav class="grow space-y-1">
    @php
    $menuItems = [
    [
    'id' => 1,
    'label' => 'Panel Control',
    'icon' => 'fa-tachometer-alt',
    'role' => 'admin',
    'sub' => [
    ['label' => 'Usuarios', 'route' => 'panel.usuarios.index', 'icon' => 'fa-users']
    ]
    ],
    [
    'id' => 2,
    'label' => 'Caja',
    'icon' => 'fa-cash-register',
    'sub' => [
    ['label' => 'Apertura', 'route' => 'caja.apertura', 'icon' => 'fa-sign-in-alt'],
    ['label' => 'Cierre', 'route' => 'caja.cierre', 'icon' => 'fa-user-slash'],
    ['label' => 'Histórico', 'route' => 'caja.historico', 'icon' => 'fa-history']
    ]
    ],
    [
    'id' => 3,
    'label' => 'Almacén',
    'icon' => 'fa-warehouse',
    'sub' => [
    ['label' => 'Categorías', 'route' => 'almacen.categoria.index', 'icon' => 'fa-list'],
    ['label' => 'Artículos', 'route' => 'almacen.articulo.index', 'icon' => 'fa-box-open'],
    ['label' => 'Inventario', 'route' => 'almacen.inventario.index', 'icon' => 'fa-boxes']
    ]
    ],
    [
    'id' => 4,
    'label' => 'Compras',
    'icon' => 'fa-shopping-cart',
    'sub' => [
    ['label' => 'Entradas', 'route' => 'compras.entrada.index', 'icon' => 'fa-truck-loading'],
    ['label' => 'Detalle', 'route' => 'compras.detalle.index', 'icon' => 'fa-file-invoice'],
    ['label' => 'Proveedores', 'route' => 'compras.proveedor.index', 'icon' => 'fa-id-card']
    ]
    ],
    [
    'id' => 5,
    'label' => 'Ventas',
    'icon' => 'fa-chart-line',
    'sub' => [
    ['label' => 'POS Venta', 'route' => 'ventas.posventa.index', 'icon' => 'fa-shopping-basket'],
    ['label' => 'Detalles', 'route' => 'ventas.detalle.index', 'icon' => 'fa-info-circle']
    ]
    ],
    [
    'id' => 6,
    'label' => 'Reportes',
    'icon' => 'fa-file-medical-alt',
    'role' => 'admin',
    'sub' => [
    ['label' => 'Gráficos', 'route' => 'empresa.reportes', 'icon' => 'fa-chart-bar']
    ]
    ],
    [
    'id' => 7,
    'label' => 'Configuración',
    'icon' => 'fa-sliders-h',
    'role' => 'admin',
    'sub' => [
    ['label' => 'Empresa', 'route' => 'configuracion.empresa.index', 'icon' => 'fa-building']
    ]
    ],
    ];
    @endphp

    @foreach($menuItems as $item)
    @if(!isset($item['role']) || auth()->user()->hasRole($item['role']))
    <div class="mb-1">
      <button x-on:click.prevent="submenuOpen{{ $item['id'] }} = !submenuOpen{{ $item['id'] }}"
        class="w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group hover:bg-white/5 @if(collect($item['sub'])->pluck('route')->contains(Route::currentRouteName())) nav-item-active text-white @endif">

        <div class="flex items-center gap-3 min-w-[24px] justify-center">
          <i class="fas {{ $item['icon'] }} text-lg group-hover:scale-110 transition-transform duration-300"></i>
        </div>

        <div x-show="open"
          class="grow flex items-center justify-between font-bold text-sm tracking-tight overflow-hidden">
          <span class="whitespace-nowrap">{{ $item['label'] }}</span>
          <i class="fas fa-chevron-right text-[10px] transition-transform duration-300"
            x-bind:class="submenuOpen{{ $item['id'] }} ? 'rotate-90' : ''"></i>
        </div>
      </button>

      <div x-show="submenuOpen{{ $item['id'] }} && open" x-collapse
        x-transition:enter="transition-all duration-300 ease-out" x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0" class="ml-6 mt-1 overflow-hidden space-y-1">
        @foreach($item['sub'] as $sub)
        <a href="{{ route($sub['route']) }}"
          class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-[13px] font-medium transition-all hover:text-white hover:bg-white/5 @if(Route::currentRouteName() == $sub['route']) text-brand @else text-slate-500 @endif">
          <i class="fas {{ $sub['icon'] }} text-[12px] opacity-40"></i>
          <span>{{ $sub['label'] }}</span>
        </a>
        @endforeach
      </div>
    </div>
    @endif
    @endforeach
  </nav>


</aside>