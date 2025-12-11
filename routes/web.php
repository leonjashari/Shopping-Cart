<?php

use App\Livewire\Catalog\Index as CatalogIndex;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', CatalogIndex::class)->name('catalog');
    Route::view('profile', 'profile')->name('profile');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/auth.php';
