<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;


Route::get('/', function () {
    return view('welcome');
});

// React Contact Form
Route::get('/', [ContactController::class, 'index']);
Route::post('/contact-store', [ContactController::class, 'store']);

// List all submitted contacts
Route::get('/contacts', [ContactController::class, 'list']);