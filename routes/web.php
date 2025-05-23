<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return \Inertia\Inertia::render('Auth/Login');
})->middleware('guest');

Route::prefix('apps')->group(function() {

    Route::group(['middleware' => ['auth']], function () {

        Route::get('dashboard', App\Http\Controllers\Apps\DashboardController::class)->name('apps.dashboard');
        
        Route::get('/permissions', \App\Http\Controllers\Apps\PermissionController::class)->name('apps.permissions.index')
            ->middleware('permission:permissions.index');

        Route::resource('/roles', \App\Http\Controllers\Apps\RoleController::class, ['as' => 'apps'])
            ->middleware('permission:roles.index|roles.create|roles.edit|roles.delete');

        Route::resource('/users', \App\Http\Controllers\Apps\UserController::class, ['as' => 'apps'])
            ->middleware('permission:users.index|users.create|users.edit|users.delete');

        Route::resource('/categories', \App\Http\Controllers\Apps\CategoryController::class, ['as' => 'apps'])
            ->middleware('permission:categories.index|categories.create|categories.edit|categories.delete');  

        Route::resource('/products', \App\Http\Controllers\Apps\ProductController::class, ['as' => 'apps'])
            ->middleware('permission:products.index|products.create|products.edit|products.delete');

        Route::resource('/customers', \App\Http\Controllers\Apps\CustomerController::class, ['as' => 'apps'])
            ->middleware('permission:customers.index|customers.create|customers.edit|customers.delete');

        Route::get('/transactions', [\App\Http\Controllers\Apps\TransactionController::class, 'index'])->name('apps.transactions.index');
        Route::post('/transactions/searchProduct', [\App\Http\Controllers\Apps\TransactionController::class, 'searchProduct'])->name('apps.transactions.searchProduct');
        Route::post('/transactions/addToCart', [\App\Http\Controllers\Apps\TransactionController::class, 'addToCart'])->name('apps.transactions.addToCart');
        Route::post('/transactions/destroyCart', [\App\Http\Controllers\Apps\TransactionController::class, 'destroyCart'])->name('apps.transactions.destroyCart');
        Route::post('/transactions/store', [\App\Http\Controllers\Apps\TransactionController::class, 'store'])->name('apps.transactions.store');
        Route::get('/transactions/print', [\App\Http\Controllers\Apps\TransactionController::class, 'print'])->name('apps.transactions.print');

        Route::get('/sales', [\App\Http\Controllers\Apps\SaleController::class, 'index'])->middleware('permission:sales.index')->name('apps.sales.index');
        Route::get('/sales/filter', [\App\Http\Controllers\Apps\SaleController::class, 'filter'])->name('apps.sales.filter');
        Route::get('/sales/export', [\App\Http\Controllers\Apps\SaleController::class, 'export'])->name('apps.sales.export');
        Route::get('/sales/pdf', [\App\Http\Controllers\Apps\SaleController::class, 'pdf'])->name('apps.sales.pdf');
        Route::get('/sales/print/{id}', [\App\Http\Controllers\Apps\SaleController::class, 'printNota'])->name('sales.printNota');
        Route::post('/sales/send-email', [\App\Http\Controllers\Apps\SaleController::class, 'sendEmail'])->name('sales.sendEmail');


        Route::get('/profits', [\App\Http\Controllers\Apps\ProfitController::class, 'index'])->middleware('permission:profits.index')->name('apps.profits.index');
        Route::get('/profits/filter', [\App\Http\Controllers\Apps\ProfitController::class, 'filter'])->name('apps.profits.filter');
        Route::get('/profits/export', [\App\Http\Controllers\Apps\ProfitController::class, 'export'])->name('apps.profits.export');
        Route::get('/profits/pdf', [\App\Http\Controllers\Apps\ProfitController::class, 'pdf'])->name('apps.profits.pdf');
    });
});