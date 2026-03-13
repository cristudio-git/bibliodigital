<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Rutas Publicas - Accesibles por todos
|--------------------------------------------------------------------------
*/
Route::get('/', [BookController::class, 'index'])->name('biblioteca.index');
Route::get('/libros/{book}/descargar', [BookController::class, 'download'])->name('libros.descargar');

/*
|--------------------------------------------------------------------------
| Rutas de Autenticacion
|--------------------------------------------------------------------------
*/
Route::middleware(['guest', 'no-cache'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/recuperar-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/recuperar-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Rutas Autenticadas - Docentes y Administrativas
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Subir libros (docentes y admin)
    Route::post('/libros', [BookController::class, 'store'])->name('libros.store');

    // Editar libros propios (docentes) o todos (admin)
    Route::get('/libros/{book}/editar', [BookController::class, 'edit'])->name('libros.edit');
    Route::put('/libros/{book}', [BookController::class, 'update'])->name('libros.update');

    // Panel de mis libros
    Route::get('/mis-libros', [BookController::class, 'myBooks'])->name('libros.mis');

    /*
    |----------------------------------------------------------------------
    | Rutas de Administracion - Solo Administrativas
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        // Gestion de usuarios (docentes)
        Route::get('/usuarios', [AdminController::class, 'users'])->name('users');
        Route::get('/usuarios/crear', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/usuarios', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/usuarios/{user}/editar', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/usuarios/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/usuarios/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

        // Gestion de todos los libros
        Route::get('/libros', [AdminController::class, 'books'])->name('books');
        Route::delete('/libros/{book}', [AdminController::class, 'destroyBook'])->name('books.destroy');
    });
});
