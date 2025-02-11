<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StructureController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/General-Product', [ProductController::class, 'index'])->name('product');
    Route::post('/General-Product', [ProductController::class, 'store'])->name('product.store');
    Route::delete('/General-Product', [ProductController::class, 'destroy'])->name('product.delete');
    
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
    Route::delete('/ERP-Operational-Employee/{id}', [UserController::class, 'destroy'])->name('emp.delete');
    
    Route::get('/ERP-Operational-Client', function () {
        return view('content.erp.erp-operational-client');
    })->name('client');
    
    Route::get('/ERP-Operational-Client-Progress', function () {
        return view('content.erp.erp-operational-progress');
    })->name('progress');

    Route::get('/checkSlugName', [UserController::class, 'checkSlugName']);
});

require __DIR__.'/auth.php';


