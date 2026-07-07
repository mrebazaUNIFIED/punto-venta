<x-app-layout>
    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-4xl font-black text-slate-950 tracking-tighter">Configuración General</h2>
                <p class="text-slate-400 font-medium mt-1">Personaliza la identidad y datos fiscales de tu negocio.</p>
            </div>
            
        </div>

        <div class="max-w-5xl mx-auto">
            <form method="POST" action="{{ route('configuracion.empresa.update', $empresa->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    
                    <!-- Left Column: Branding & Logo -->
                    <div class="lg:col-span-4 space-y-6">
                        <div class="bg-white/70 backdrop-blur-xl border border-white p-8 rounded-[3rem] shadow-[0_30px_60px_rgba(0,0,0,0.04)] relative overflow-hidden group text-center">
                            <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-600/5 rounded-full blur-3xl"></div>
                            
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-10 italic">Logo del Negocio</h3>
                            
                            <div class="relative inline-block group/logo">
                                <div class="w-48 h-48 mx-auto rounded-[3rem] bg-slate-50 border-2 border-dashed border-slate-200 flex items-center justify-center overflow-hidden transition-all duration-500 group-hover/logo:border-blue-200 group-hover/logo:scale-105 shadow-inner">
                                    @if ($empresa->logo)
                                        <img src="{{ asset('storage/logos/' . $empresa->logo) }}"
                                             alt="Logo {{ $empresa->nombre_comercial }}"
                                             class="w-full h-full object-contain p-4 drop-shadow-2xl">
                                    @else
                                        <div class="flex flex-col items-center gap-3 opacity-30 group-hover/logo:opacity-100 transition-opacity">
                                            <i class="fas fa-image text-5xl text-slate-300"></i>
                                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Sin Logo</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="mt-8 relative h-14 w-full">
                                    <input type="file" name="logo" class="absolute inset-0 w-full h-full opacity-0 z-10 cursor-pointer">
                                    <div class="w-full h-full bg-slate-900 text-white rounded-2xl flex items-center justify-center gap-3 font-black uppercase tracking-widest text-[10px] transition-all hover:bg-slate-800 shadow-xl shadow-slate-200 active:scale-95">
                                        <i class="fas fa-cloud-upload-alt text-lg"></i> Subir Nuevo Logo
                                    </div>
                                </div>
                                <p class="mt-4 text-[9px] text-slate-400 font-bold uppercase tracking-widest italic opacity-50">PNG, JPG o JPEG (Max. 2MB)</p>
                            </div>
                        </div>

                      
                    </div>

                    <!-- Right Column: Business Data -->
                    <div class="lg:col-span-8 bg-white/70 backdrop-blur-xl border border-white p-10 rounded-[3rem] shadow-[0_40px_80px_rgba(0,0,0,0.05)] relative overflow-hidden">
                        <div class="absolute -top-10 -left-10 w-40 h-40 bg-blue-600/5 rounded-full blur-3xl"></div>
                        
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-12 italic border-b border-slate-50 pb-6">Información Fiscal y de Contacto</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-8">
                            @foreach([
                                'razon_social' => ['Razón Social', 'Ej: Mi Empresa S.A.C', 'fas fa-building'],
                                'nombre_comercial' => ['Nombre Comercial', 'Ej: Minimarket Express', 'fas fa-store'],
                                'ruc' => ['RUC', 'Número de 11 dígitos', 'fas fa-id-card'],
                                'direccion_fiscal' => ['Dirección Fiscal', 'Dirección completa', 'fas fa-map-marker-alt'],
                                'ubigeo' => ['Ubigeo', 'Código de 6 dígitos', 'fas fa-map-pin'],
                                'departamento' => ['Departamento', 'Ej: Lima', 'fas fa-map'],
                                'provincia' => ['Provincia', 'Ej: Lima', 'fas fa-map'],
                                'distrito' => ['Distrito', 'Ej: Miraflores', 'fas fa-map'],
                                'telefono' => ['Teléfono / WhatsApp', '+51 987 654 321', 'fas fa-phone'],
                                'correo' => ['Correo Electrónico', 'hola@negocio.com', 'fas fa-envelope']
                            ] as $campo => $info)
                                <div class="space-y-2 group">
                                    <label class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic group-focus-within:text-brand transition-colors">
                                        <i class="{{ $info[2] }} opacity-50"></i>
                                        {{ $info[0] }}
                                    </label>
                                    <input type="{{ $campo === 'correo' ? 'email' : 'text' }}"
                                           name="{{ $campo }}"
                                           placeholder="{{ $info[1] }}"
                                           value="{{ old($campo, $empresa->$campo) }}"
                                           class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-8 focus:ring-blue-500/5 text-slate-900 font-bold transition-all outline-none placeholder:text-slate-300">
                                </div>
                            @endforeach
                        </div>

                        <div class="pt-12 mt-12 border-t border-slate-50 flex items-center justify-between">
                            <div class="hidden md:flex items-center gap-3">
                               
                            </div>
                            <button type="submit"
                                    class="w-full md:w-auto px-10 py-5 bg-brand hover:bg-brand-hover text-white rounded-[2rem] font-black uppercase tracking-[0.2em] text-sm shadow-2xl shadow-blue-200 transition-all active:scale-95 flex items-center justify-center gap-4">
                                <i class="fas fa-save text-lg"></i> Guardar Cambios
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
