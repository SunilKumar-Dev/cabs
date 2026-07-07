<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Users\UserIndex;
use App\Livewire\Users\UserCreate;
use App\Livewire\Users\UserEdit;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
  
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::middleware('role:Admin|Superadmin')->group(function () {

        // User routes
        Route::get('/users', UserIndex::class)
            ->middleware('permission:view_users')
            ->name('users.index');

        Route::get('/users/create', UserCreate::class)
            ->middleware('permission:create_users')
            ->name('users.create');

        Route::get('/users/{user}/edit', UserEdit::class)
            ->middleware('permission:edit_users')
            ->name('users.edit');
    });
    
});

require __DIR__.'/settings.php';
require __DIR__.'/vehicles.php';
