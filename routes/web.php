<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PaymentController;

// use App\Http\Controllers\PaymentController as PayService;

use App\Http\Controllers\Home\BasketController;
use App\Http\Controllers\Home\CheckoutController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Home\ProductController as HomeProductController;
use App\Http\Controllers\PaymentControllerCopy;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('')->group( function () {
    Route::get('' , [HomeController::class , 'index'])->name('home.page');
    Route::get('/product/{product_id}' , [HomeProductController::class , 'show'])->name('home.product.show');
    
    Route::get('/add-to-basket/{product_id}'      , [BasketController::class , 'addToBasket'])->name('home.addToBasket');
    Route::get('/remove-from-basket/{product_id}' , [BasketController::class , 'removeFromBasket'])->name('home.removeFromBasket');
   
    Route::get('/checkout/show' , [CheckoutController::class , 'show'])->name('home.checkout.show');
    
});


Route::prefix('payment')->group(function(){
    Route::post('pay' , [PaymentControllerCopy::class , 'pay'] )->name('payment.pay');
    Route::post('callback' , [PaymentControllerCopy::class , 'callback'] )->name('payment.callback');;
});


Route::prefix('admin-panel')->group(function(){
    Route::get('/' , function () {return view('frontend.panel.admin'); });
    
    Route::get('/orders' , [OrderController::class , 'index' ] )->name('orders.list') ;
    Route::get('/orders-item/{order_item}' , [OrderController::class , 'get_orderItem' ] )->name('orderItem.list') ;
    
    Route::get('/payments' , [PaymentController::class , 'index' ] )->name('payments.list') ;
    
    Route::prefix('category')->group(function(){
        Route::get('' , [CategoryController::class , 'index'])->name('category.list');
       
        Route::get('/form' , [CategoryController::class , 'add_form'])->name('category.form') ;
        Route::post('/added' , [CategoryController::class , 'added'])->name('category.added');   
       
        Route::delete('/delete/{category_id}' , [CategoryController::class , 'delete'])->name('category.delete'); 

        Route::get('/edit/{category_id}' , [CategoryController::class , 'edit'])->name('category.edit.form');   
        Route::put('/updated/{category_id}' , [CategoryController::class , 'update'])->name('category.updated');   
    });

    Route::prefix('/product')->group(function(){
        Route::get('' , [ProductController::class , 'index'])->name('product.list');

        Route::get('/form' , [ProductController::class , 'add_form'])->name('product.form');
        Route::post('/added' , [ProductController::class , 'added'])->name('product.added');
        
        Route::get('/{product_id}/download/demo' , [ProductController::class , 'download_demo'])->name('product.demo');
        Route::get('/{product_id}/download/source' , [ProductController::class , 'download_source'])->name('product.source');
        
        Route::delete('/delete/{product_id}' , [ProductController::class , 'delete'])->name('product.delete');
        
        Route::get('/edit/{product_id}' , [ProductController::class , 'edit'])->name('product.edit.form');
        Route::put('/update/{product_id}' , [ProductController::class , 'update'])->name('product.update');

    });

    Route::prefix('/users')->group(function(){
        Route::get('' , [UserController::class , 'index' ])->name('users.list');
        
        Route::get('/form' , [UserController::class , 'add_form' ])->name('user.add.form');
        Route::post('/added' , [UserController::class , 'added' ])->name('user.added');
        
        Route::get('/edit/{user_id}' , [UserController::class , 'edit' ])->name('user.edit.form');
        Route::put('/update/{user_id}' , [UserController::class , 'update' ])->name('user.update');
        
        Route::delete('/delete/{user_id}' , [UserController::class , 'delete' ])->name('user.delete');
        
    });
    
    
});

