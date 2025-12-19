<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;


Route::get('/', function () {
    return view('welcome');
});




Route::get('/', [ContactController::class, 'index']);
Route::post('/contact-store', [ContactController::class, 'store']);
