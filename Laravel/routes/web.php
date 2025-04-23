<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ExhibitController;
use App\Http\Controllers\ExhibitionController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/test', [TestController::class, 'test']);
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'new'])->name('products.new');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
Route::post('/products/{id}/delete', [ProductController::class, 'delete'])->name('products.delete');

//museum
Route::resource('exhibits', ExhibitController::class);
Route::resource('exhibitions', ExhibitionController::class);
Route::resource('staff', StaffController::class);
Route::resource('visitors', VisitorController::class);
Route::resource('tickets', TicketController::class);
Route::get('tickets/{ticket}/buy', [TicketController::class, 'buy'])->name('tickets.buy');
Route::get('tickets/{ticket}/sell', [TicketController::class, 'sell'])->name('tickets.sell');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');