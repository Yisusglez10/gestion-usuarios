<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    {{-- Detectar errores para reabrir modal --}}
    <div x-data="{ openModal: {{ $errors->any() ? 'true' : 'false' }} }" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div
                            x-data="{ show: true }"
                            x-init="setTimeout(() => show = false, 3000)"
                            x-show="show"
                            class="mb-4 text-green-600 font-semibold transition ease-in-out duration-300"
                        >
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div
                            x-data="{ show: true }"
                            x-init="setTimeout(() => show = false, 4000)"
                            x-show="show"
                            class="mb-4 text-red-600 font-semibold transition ease-in-out duration-300"
                        >
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-xl font-bold">Lista de Usuarios</h1>

                        @if (auth()->user()->hasRole('admin'))
                            <button
                                @click="openModal = true"
                                class="bg-green-700 hover:bg-green-600 text-white px-4 py-2 rounded"
                            >
                                Crear Nuevo Usuario
                            </button>
                        @endif
                    </div>

                    {{-- Tabla de usuarios --}}
                    <table class="table-auto w-full text-left border-collapse border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border px-4 py-2">ID</th>
                                <th class="border px-4 py-2">Nombre</th>
                                <th class="border px-4 py-2">Correo</th>
                                <th class="border px-4 py-2">Rol</th>
                                @if (auth()->user()->hasAnyRole(['admin', 'editor']))
                                    <th class="border px-4 py-2">Acciones</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="border px-4 py-2">{{ $user->id }}</td>
                                    <td class="border px-4 py-2">{{ $user->name }}</td>
                                    <td class="border px-4 py-2">{{ $user->email }}</td>
                                    <td class="border px-4 py-2">{{ $user->roles->pluck('name')->implode(', ') }}</td>

                                    @if (auth()->user()->hasAnyRole(['admin', 'editor']))
                                        <td class="border px-4 py-2">
                                            <a href="#" class="text-blue-600 hover:underline">Editar</a>
                                            @if (auth()->user()->hasRole('admin'))
                                               <form method="POST" action="{{ route('users.destroy', $user) }}" 
                                                     onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');" 
                                                     style="display:inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                                                </form>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Modal --}}
        <div
            x-show="openModal"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        >
            <div @click.away="openModal = false" class="bg-white rounded-lg p-6 w-full max-w-md">
                <h2 class="text-lg font-semibold mb-4">Crear Usuario</h2>

                {{-- Mostrar errores --}}
                @if ($errors->any())
                    <div class="mb-4 text-red-600 text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('users.store') }}" autocomplete="off">
                    @csrf

                    <input type="hidden" name="fakefield" autocomplete="false" style="display:none">

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Nombre</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            class="w-full border rounded px-3 py-2"
                            autocomplete="off"
                        >
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Correo</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            class="w-full border rounded px-3 py-2"
                            autocomplete="off"
                        >
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Rol</label>
                        <select name="role" required class="w-full border rounded px-3 py-2">
                            <option value="">Seleccionar rol</option>
                            @foreach (Spatie\Permission\Models\Role::all() as $role)
                                <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Contraseña</label>
                        <input
                            type="password"
                            name="password"
                            value=""
                            required
                            class="w-full border rounded px-3 py-2"
                            autocomplete="new-password"
                        >
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Confirmar Contraseña</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            value=""
                            required
                            class="w-full border rounded px-3 py-2"
                            autocomplete="new-password"
                        >
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="openModal = false" class="px-4 py-2 border rounded">
                            Cancelar
                        </button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
