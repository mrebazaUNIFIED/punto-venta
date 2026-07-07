<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-blue-500/30']) }}>
    {{ $slot }}
</button>
