<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

 Route::middleware([
    'cache.headers:no_store;no_cache;must_revalidate;max_age=0',
 ])->group(static function(){
    Route::controller(LoginController::class)
        ->prefix('auth')
        ->group(function() {
        // name this route to login by default setting.
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'authenticate')->name('authenticate');
        Route::get('/logout', 'logout')->name('logout');
    });

    Route::middleware(['auth'])->group(function() {
        Route::controller(ProductController::class)
            ->prefix('products')
            ->name('products.')
            ->group(function () {
                Route::get('', 'list')->name('list');
                Route::get('/create', 'showCreateForm')->name('create-form');
                Route::post('/create', 'create')->name('create');
                Route::prefix('/{product}')->group(static function(){
                    Route::get('', 'show')->name('view');
                    Route::prefix('/shops')->group(static function(){
                        Route::get('', 'showShops')->name('view-shops');
                        Route::get('/add', 'showAddShopsForm')->name('add-shops-form');
                        Route::post('/add', 'addShop')->name('add-shops');
                        Route::get('/{shop}', 'remove')->name('remove-shop');
                });
                Route::get('/update', 'showUpdateForm')->name('update-form');
                Route::post('/update', 'update')->name('update');
                Route::get('/delete', 'delete')->name('delete');     
            });
    });

    Route::controller (ShopController::class)
        ->prefix('shops')
        ->name('shops.')
        ->group (static function () {
            Route::get('', 'list')->name('list');
            Route::get('/create', 'showCreateForm')->name('create-form'); 
            Route::post('/create', 'create')->name('create');
            Route::prefix('/{shop}')->group(static function () {
                Route::get('', 'show')->name('view');
                Route::prefix('/products')->group(static function () {   
                    Route::get('', 'showProducts')->name('view-products');
                    Route::get('/add', 'showAddProductsForm')->name('add-products-form'); 
                    Route::post('/add', 'addProduct')->name('add-product');
                    Route::get('/{product}/remove', 'removeProduct')->name('remove-product');
                });
                Route::get('/update', 'showUpdateForm')->name('update-form');
                Route::post('/update', 'update')->name('update'); 
                Route::get('/delete', 'delete')->name('delete');
            });
    });

    Route::controller (CategoryController::class)
        ->prefix('categories')
        ->name('categories.')
        ->group (static function () {
            Route::get('', 'list')->name('list');
            Route::get('/create', 'showCreateForm')->name('create-form'); 
            Route::post('/create', 'create')->name('create');
            Route::prefix('/{category}')->group(static function () {
                Route::get('', 'show')->name('view');
                Route::prefix('/products')->group(static function () {
                    Route::get('', 'showProducts')->name('view-products');
                    Route::get('/add', 'showAddProductsForm')->name('add-products-form');
                    Route::post('/add', 'addProduct')->name('add-product');
                });
                Route::get('/update', 'showUpdateForm')->name('update-form'); 
                Route::post('/update', 'update')->name("update"); 
                Route::get('/delete', 'delete')->name('delete');
            });
        });
    });
    Route::middleware(['auth'])->group(function () {
        Route::controller(UserController::class)
            ->prefix('users')
            ->name('users.')
            ->group(function () {
                Route::get('', 'list')->name('list');
                Route::get('/create', 'showCreateForm')->name('create-form');
                Route::post('/create', 'create')->name('create');
                Route::get('/{email}', 'show')->name('view');
                Route::get('/{user}/edit', 'showEditForm')->name('edit-form');
                Route::put('/{user}', 'update')->name('update');
                Route::delete('/{user}', 'delete')->name('delete');
                Route::get('/self/{id}', 'showSelf')->name('self');
                Route::get('/self/{userId}/edit', 'showUpdateSelf')->name('update-self');
                Route::put('/self/{userId}', 'updateSelf')->name('update-self-submit');
            });
    });
 });

