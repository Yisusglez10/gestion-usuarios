<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex items-center justify-between mb-4">
                    <h1 class="text-xl font-bold">Lista de Usuarios</h1>

                    @if (auth()->user()->hasRole('admin'))
                        <a href="#" class="bg-green-700 hover:bg-green-600 text-white px-4 py-2 rounded">
                            Crear Nuevo Usuario
                        </a>
                    @endif
                    </div>

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
                                                | <a href="#" class="text-red-600 hover:underline">Eliminar</a>
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
    </div>
</x-app-layout>
