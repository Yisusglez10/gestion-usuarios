# 🛠️ Gestión de Usuarios con Laravel

Este proyecto es un dashboard administrativo hecho en Laravel 12 con autenticación, gestión de roles y permisos usando el paquete `spatie/laravel-permission`. Incluye un CRUD de usuarios con diseño profesional y soporte para modales y paginación.

---

## 🚀 Funcionalidades

- ✅ Autenticación de usuarios
- ✅ CRUD de usuarios (crear, editar, eliminar)
- ✅ Asignación de roles (admin, editor, viewer)
- ✅ Permisos personalizados con Spatie
- ✅ Vistas condicionales según el rol
- ✅ Interfaz moderna con TailwindCSS
- ✅ Paginación y búsqueda en tabla
- ✅ Mensajes de éxito/error automáticos

---

## 🔐 Accesos por defecto

| Rol    | Email                  | Contraseña |
|--------|------------------------|------------|
| Admin  | `admin@admin.com`     | `admin`    |
| Editor | `editor@editor.com`   | `editor`   |
| Viewer | `viewer1@gmail.com`   | `viewer`   |

Se generaron 10 usuarios tipo *viewer* (`viewer1@gmail.com` hasta `viewer10@gmail.com`) para visualizar la paginación.

---

## ⚙️ Modos de acceso disponibles

### 🛡️ Modo 1 — Acceso exclusivo para Admin

Permite que solo los usuarios con rol `admin` accedan al dashboard.

Para activarlo:

1. En `routes/web.php`, descomenta estas líneas:

```php
Route::get('/', fn () => redirect('/redirect-by-role'));

Route::get('/sin-acceso', fn () => view('errors.no-access'));

Route::get('/redirect-by-role', function () {
    $user = Auth::user();

    if ($user->hasRole('admin')) {
        return redirect()->route('dashboard');
    }

    return redirect('/sin-acceso');
})->middleware(['auth']);

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});
```

2. En `AuthenticatedSessionController.php`, reemplaza la línea de redirección por:

```php
$user = Auth::user();
if ($user->hasRole('admin')) {
    return redirect()->route('dashboard');
}
return redirect('/sin-acceso');
```

> ⚠️ Esto hará que cualquier usuario que no sea admin reciba una vista personalizada de **acceso denegado** con opción para cerrar sesión.

---

### 🧩 Modo 2 — Acceso por rol (modo flexible)

Permite que cualquier usuario autenticado acceda al dashboard. Lo que se muestre dependerá del rol del usuario.

Para activarlo:

1. En `routes/web.php`, descomenta estas líneas:

```php
Route::get('/', fn () => redirect('/dashboard'));

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});
```

2. En `AuthenticatedSessionController.php`, usa esta redirección al autenticar:

```php
return redirect()->intended(route('dashboard', absolute: false));
```

---

## 📷 Capturas de pantalla


---

## 📦 Instalación

```bash
git clone https://github.com/tu-usuario/gestion-usuarios.git
cd gestion-usuarios
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run dev
php artisan serve
```

---

## 🧠 Paquetes clave usados

- `laravel/breeze` (autenticación ligera)
- `spatie/laravel-permission` (roles y permisos)
- `tailwindcss` (diseño)
- `alpine.js` (modales y comportamiento UI)

---

## 🧑‍💻 Autor

Desarrollado por **[Jesús González]** — como parte del proceso técnico para Prospektiva.
