<?php

use App\Http\Controllers\PackageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
// use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StructureController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('services', ServiceController::class);
    Route::resource('packages', PackageController::class);

    Route::get('/General-Product', [ProductController::class, 'index'])->name('product');
    Route::post('/General-Product', [ProductController::class, 'store'])->name('product.store');
    Route::put('/General-Product/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/General-Product/{id}', [ProductController::class, 'destroy'])->name('product.delete');
    
    Route::get('/General-Organization-Structure', [StructureController::class, 'index'])->name('organization');
    Route::put('/General-Organization-Structure/update/{id}', [StructureController::class, 'update'])->name('role.update');
    
    Route::get('ANIs-Service-List', function () {
        return view('content.anis.anis-service-list');
    })->name('service');
    
    Route::get('ANIs-Package', function () {
        return view('content.anis.anis-package');
    })->name('package');
    
    Route::get('/ANIs-Portfolio', function () {
        return view('content.anis.anis-portfolio');
    })->name('portfolio');
    
    
    Route::get('/ERP-Finance-Expanses-Tracker', function () {
        return view('content.erp.erp-finance-expanses');
    })->name('expanses');
    
    Route::get('/ERP-Finance-Audit', function () {
        return view('content.erp.erp-finance-audit');
    })->name('audit');
    
    Route::get('/ERP-Finance-Invoicing', function () {
        return view('content.erp.erp-finance-invoicing');
    })->name('invoicing');
    
    Route::get('/ERP-Operational-Employee', [UserController::class, 'index'])->name('emp');
    Route::post('/ERP-Operational-Employee/store', [UserController::class, 'store'])->name('emp.store');
    Route::put('/ERP-Operational-Employee/update/{id}', [UserController::class, 'update'])->name('emp.update');
    Route::delete('/ERP-Operational-Employee/{id}', [UserController::class, 'destroy'])->name('emp.delete');
    
    Route::get('/ERP-Operational-Client', [ClientController::class, 'index'])->name('client');
    Route::post('/ERP-Operational-Client/store', [ClientController::class, 'store'])->name('client.store');
    Route::put('/ERP-Operational-Client/{id}', [ClientController::class, 'update'])->name('client.update');
    Route::delete('/ERP-Operational-Client/{id}', [ClientController::class, 'destroy'])->name('client.delete');
    
    Route::get('/ERP-Operational-Client-Progress', [OrderController::class, 'index'])->name('progress');
    Route::post('/ERP-Operational-Client-Progress/store', [OrderController::class, 'store'])->name('progress.store');
    Route::put('/ERP-Operational-Client-Progress/{id}', [OrderController::class, 'update'])->name('progress.update');
    Route::delete('/ERP-Operational-Client-Progress/{id}', [OrderController::class, 'destroy'])->name('progress.delete');

    Route::get('/checkSlugName', [UserController::class, 'checkSlugName']);
    Route::get('/amount', [OrderController::class, 'amount']);
});

    Route::get('/Profile', function () {
        return view('content.profil.profil-edit');
    })->name('profil');

    Route::get('/Profile-Connection', function () {
        return view('content.profil.profil-connection');
    })->name('connection');
    
    



Route::get('/Profile', function () {
    return view('content.profil.profil-edit');
})->name('profil');



require __DIR__.'/auth.php';