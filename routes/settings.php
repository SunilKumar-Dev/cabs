<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Route::livewire('settings/profile', 'pages::settings.profile')->name('profile.edit');
    Route::middleware('role:Admin|Superadmin')->group(function () {
        Route::livewire('settings/roles', 'pages::settings.roles')->name('roles.index');
    });

    Route::middleware('role:Superadmin')->group(function () {
        Route::livewire('settings/companyinfo', 'pages::settings.company-info')->name('companyinfo.index');
        Route::livewire('settings/permissions', 'pages::settings.permissions')->name('permissions.index');
    });


});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('settings/appearance', 'pages::settings.appearance')->name('appearance.edit');

    Route::livewire('settings/security', 'pages::settings.security')
        ->middleware([
            'password.confirm',
        ])
        ->name('security.edit');
        
});

Route::get('.well-known/passkey-endpoints', function () {
    return response()->json([
        'enroll' => route('security.edit'),
        'manage' => route('security.edit'),
    ]);
})->name('well-known.passkeys');
