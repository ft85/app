<?php

use Illuminate\Http\Request;
use App\Http\Controllers\RegController;
use App\Http\Controllers\InjongeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/consumer_check', [RegController::class, 'consumer_check']);

Route::post('/buytoken', [RegController::class, 'buytoken']);


Route::post('/loginSerial', [InjongeController::class, 'getInfoSerial']);
   
 Route::post('/getItems', [InjongeController::class, 'getStockItems']);

 Route::post('/sales', [InjongeController::class, 'postsales']);

 Route::post('/getContacts', [InjongeController::class, 'getcontacts']);
 
 Route::post('/addContact', [InjongeController::class, 'addCustomer']);

 Route::post('/editContact', [InjongeController::class, 'editCustomer']);

 Route::post('/addItem', [InjongeController::class, 'addProduct']);

 Route::post('/editItem', [InjongeController::class, 'editProduct']);


 Route::post('/getCategories', [InjongeController::class, 'getcategories']);

 Route::post('/getItemUpdates', [InjongeController::class, 'getitemupdates']);

 Route::post('/salesreturn', [InjongeController::class, 'postreturn']);

 Route::post('/verifyTIN', [InjongeController::class, 'verifyTaxNumber']);

 Route::post('/salescreditnote', [InjongeController::class, 'postcreditnote']);

 





 

