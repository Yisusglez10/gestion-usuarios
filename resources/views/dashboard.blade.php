<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div
        x-data="{
            openCreateModal: {{ $errors->any() && session('form_type') === 'create' ? 'true' : 'false' }},
            openEditModal: {{ $errors->any() && session('form_type') === 'edit' ? 'true' : 'false' }},
            form: {
                id: {{ session('form_type') === 'edit' ? old('id', 'null') : 'null' }},
                name: '{{ old('name') }}',
                email: '{{ old('email') }}',
                role: '{{ old('role') }}',
            }
        }"
        class="py-12"
    >
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" class="mb-4 text-green-600 font-semibold">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" class="mb-4 text-red-600 font-semibold">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-xl font-bold">Lista de Usuarios</h1>
                        @if (auth()->user()->hasRole('admin'))
                            <button @click="openCreateModal = true" class="bg-green-700 hover:bg-green-600 text-white px-4 py-2 rounded">
                                Crear Nuevo Usuario
                            </button>
                        @endif
                    </div>
                    <div class="overflow-x-auto">
                        <input
                            type="text"
                            id="searchInput"
                            placeholder="Buscar..."
                            class="mb-4 p-2 border rounded w-full md:w-1/3"
                        />
                        <table 
                            x-data="tableData()" 
                            class="table-auto w-full text-left border-collapse border border-gray-200"
                            id="userTable"
                        >
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-4 py-2 cursor-pointer" @click="sort('id')">
                                        ID 
                                        <span x-show="sortColumn !== 'id'" class="text-gray-400">↑↓</span>
                                        <span x-show="sortColumn === 'id'" x-text="sortAsc ? '↑' : '↓'"></span>
                                    </th>
                                    <th class="border px-4 py-2 cursor-pointer" @click="sort('name')">
                                        Nombre 
                                        <span x-show="sortColumn !== 'name'" class="text-gray-400">↑↓</span>
                                        <span x-show="sortColumn === 'name'" x-text="sortAsc ? '↑' : '↓'"></span>
                                    </th>
                                    <th class="border px-4 py-2 cursor-pointer" @click="sort('email')">
                                        Correo 
                                        <span x-show="sortColumn !== 'email'" class="text-gray-400">↑↓</span>
                                        <span x-show="sortColumn === 'email'" x-text="sortAsc ? '↑' : '↓'"></span>
                                    </th>
                                    <th class="border px-4 py-2 cursor-pointer" @click="sort('role')">
                                        Rol 
                                        <span x-show="sortColumn !== 'role'" class="text-gray-400">↑↓</span>
                                        <span x-show="sortColumn === 'role'" x-text="sortAsc ? '↑' : '↓'"></span>
                                    </th>
                                    <th class="border px-4 py-2">Acciones</th>
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
                                                <div class="flex items-center gap-3">
                                                    {{-- Botón Editar --}}
                                                    <button
                                                        @click.prevent="
                                                            openEditModal = true;
                                                            form.id = {{ $user->id }};
                                                            form.name = '{{ $user->name }}';
                                                            form.email = '{{ $user->email }}';
                                                            form.role = '{{ $user->roles->first()->name ?? '' }}';
                                                        "
                                                        class="text-blue-600 hover:text-blue-800 p-1"
                                                        title="Editar"
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M15.232 5.232l3.536 3.536M4 20h4l10-10-4-4L4 16v4z"/>
                                                        </svg>
                                                    </button>

                                                    {{-- Botón Eliminar --}}
                                                    @if (auth()->user()->hasRole('admin'))
                                                        <form method="POST" action="{{ route('users.destroy', $user) }}"
                                                            onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');"
                                                            class="p-0 m-0"
                                                        >
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-800 p-1" title="Eliminar">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M19 7L5 7M6 7v12a2 2 0 002 2h8a2 2 0 002-2V7M9 10v6m6-6v6M10 4h4a1 1 0 011 1v1H9V5a1 1 0 011-1z"/>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
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

        {{-- Modal Crear --}}
        <div x-show="openCreateModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div @click.away="openCreateModal = false" class="bg-white rounded-lg p-6 w-full max-w-md">
                <h2 class="text-lg font-semibold mb-4">Crear Usuario</h2>

                @if ($errors->any() && session('form_type') === 'create')
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
                    <input type="hidden" name="_action" value="create">
                    <input type="hidden" name="fakefield" autocomplete="false" style="display:none">

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Nombre</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full border rounded px-3 py-2" autocomplete="off">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Correo</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full border rounded px-3 py-2" autocomplete="off">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Rol</label>
                        <select name="role" required class="w-full border rounded px-3 py-2">
                            <option value="">Seleccionar rol</option>
                            @foreach (Spatie\Permission\Models\Role::all() as $role)
                                <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Contraseña</label>
                        <input type="password" name="password" required class="w-full border rounded px-3 py-2" autocomplete="new-password">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" required class="w-full border rounded px-3 py-2" autocomplete="new-password">
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="openCreateModal = false" class="px-4 py-2 border rounded">Cancelar</button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Editar --}}
        <div x-show="openEditModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div @click.away="openEditModal = false" class="bg-white rounded-lg p-6 w-full max-w-md">
                <h2 class="text-lg font-semibold mb-4">Editar Usuario</h2>

                 @if ($errors->any() && session('form_type') === 'edit')
                    <div class="mb-4 text-red-600 text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form :action="'/users/' + form.id" method="POST" autocomplete="off">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="id" :value="form.id">

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Nombre</label>
                        <input type="text" name="name" x-model="form.name" required class="w-full border rounded px-3 py-2" autocomplete="off">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Correo</label>
                        <input type="email" name="email" x-model="form.email" required class="w-full border rounded px-3 py-2" autocomplete="off">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Rol</label>
                        <select name="role" x-model="form.role" required class="w-full border rounded px-3 py-2">
                            <option value="">Seleccionar rol</option>
                            @foreach (Spatie\Permission\Models\Role::all() as $role)
                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Nueva Contraseña (opcional)</label>
                        <input type="password" name="password" class="w-full border rounded px-3 py-2" autocomplete="new-password">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2" autocomplete="new-password">
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="openEditModal = false" class="px-4 py-2 border rounded">Cancelar</button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('tableData', () => ({
            sortColumn: '',
            sortAsc: true,
            sort(column) {
                const table = document.getElementById('userTable').getElementsByTagName('tbody')[0];
                const rows = Array.from(table.querySelectorAll('tr'));

                const columnIndex = {
                    id: 0,
                    name: 1,
                    email: 2,
                    role: 3,
                }[column];

                rows.sort((a, b) => {
                    let valA = a.children[columnIndex].innerText.trim().toLowerCase();
                    let valB = b.children[columnIndex].innerText.trim().toLowerCase();
                    
                    return (this.sortAsc ? 1 : -1) * valA.localeCompare(valB);
                });

                this.sortAsc = this.sortColumn === column ? !this.sortAsc : true;
                this.sortColumn = column;

                rows.forEach(row => table.appendChild(row));
            }
        }));
    });

    // Buscador
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('searchInput');
        const tableRows = document.querySelectorAll('#userTable tbody tr');

        input.addEventListener('input', function () {
            const query = this.value.toLowerCase();

            tableRows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    });
</script>
