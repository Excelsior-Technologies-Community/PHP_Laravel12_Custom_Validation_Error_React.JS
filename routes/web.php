<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

// React Contact Form
Route::get('/', [ContactController::class, 'index']);
Route::post('/contact-store', [ContactController::class, 'store']);

// Contact Management Routes
Route::get('/contacts', [ContactController::class, 'list'])->name('contacts.list');
Route::get('/contacts/{id}', [ContactController::class, 'show']);
Route::delete('/contacts/{id}', [ContactController::class, 'destroy']);
Route::post('/contacts/bulk-delete', [ContactController::class, 'bulkDelete']);
Route::get('/contacts/export/csv', [ContactController::class, 'export'])->name('contacts.export');