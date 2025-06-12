<x-guest-layout>
    <div class="text-center mt-20">
        <h1 class="text-3xl font-bold text-red-600">Acceso Denegado</h1>
        <p class="mt-4 text-gray-600">No tienes permisos para acceder a esta sección.</p>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="mt-6 inline-block text-black px-4 py-2 rounded">
            Cerrar sesión
        </a>
        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
            @csrf
        </form>
    </div>
</x-guest-layout>
