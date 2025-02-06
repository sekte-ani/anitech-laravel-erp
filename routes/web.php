<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/', function () {
    return view('content.dashboard');
});
Route::get('/General-Product', function () {
    return view('content.general.general-product');
})->name('product');

Route::get('/General-Organization-Structure', function () {
    return view('content.general.general-organization-struc');
})->name('organization');

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

Route::get('/ERP-Operational-Employee', function () {
    return view('content.erp.erp-operational-employee');
})->name('emp');

Route::get('/ERP-Operational-Client', function () {
    return view('content.erp.erp-operational-client');
})->name('client');

Route::get('/ERP-Operational-Client-Progress', function () {
    return view('content.erp.erp-operational-progress');
})->name('progress');



require __DIR__.'/auth.php';


