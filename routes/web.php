<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// // Si se desea que solo admin acceda al dashboard:
// Route::get('/', fn () => redirect('/redirect-by-role'));

// Route::get('/sin-acceso', function () {
//     return view('errors.no-access');
// });

// Route::get('/redirect-by-role', function () {
//     $user = Auth::user();

//     if ($user->hasRole('admin')) {
//         return redirect()->route('dashboard');
//     }

//     return redirect('/sin-acceso'); 
// })->middleware(['auth']);

// Route::middleware(['auth', 'role:admin'])->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
//     Route::post('/users', [UserController::class, 'store'])->name('users.store');
//     Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
//     Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
// });

// Para acceder a diferentes vistas depende el rol descomentar esto
Route::get('/', fn () => redirect('/dashboard'));
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

require __DIR__.'/auth.php';
