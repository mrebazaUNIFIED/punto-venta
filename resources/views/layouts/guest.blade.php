<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased bg-white overflow-x-hidden">
  <div class="min-h-screen h-full flex flex-col lg:flex-row">
    <!-- Left Side: Hero Image -->
    <div class="hidden lg:flex lg:w-1/2 relative bg-slate-900 min-h-screen">
      <img src="{{ asset('images/auth-hero.png') }}" alt="POS System Hero"
        class="absolute inset-0 w-full h-full object-cover opacity-60">
      <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>

      <div class="relative z-10 flex flex-col justify-end p-16 w-full h-full">
        <div class="max-w-md">

          <h2 class="text-5xl font-black text-white leading-[1.1] mb-6">Potencia tu negocio con tecnología
            inteligente.</h2>
          <p class="text-slate-300 text-lg font-medium leading-relaxed">Gestión de ventas, inventarios y
            reportes en una sola plataforma robusta y fácil de usar.</p>
        </div>
      </div>
    </div>

    <!-- Right Side: Form Area -->
    <div class="w-full lg:w-1/2 flex flex-col relative bg-[#eaeaec] overflow-hidden">
      <!-- Dynamic Background Element -->
      <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-[20%] -right-[10%] w-[60%] h-[60%] bg-blue-50/50 rounded-full blur-[120px]">
        </div>
        <div class="absolute -bottom-[20%] -left-[10%] w-[60%] h-[60%] bg-blue-50/50 rounded-full blur-[120px]">
        </div>
      </div>



      <!-- Central Content (Form) -->
      <div class="grow flex flex-col items-center justify-center p-6 sm:p-12 md:p-20 relative z-10">
        <div class="max-w-xl w-full">
          {{ $slot }}
        </div>
      </div>

      <!-- Footer (Always Bottom) -->
      <div class="p-8 text-center text-slate-400 text-[10px] font-bold uppercase tracking-widest relative z-10">
        &copy; {{ date('Y') }} {{ config('app.name', 'POS') }}. Estándar de Excelencia Comercial.
      </div>
    </div>
  </div>
</body>

</html>