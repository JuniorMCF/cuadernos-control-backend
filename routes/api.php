<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\EnterpriseController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RecordController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\StatisticController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Profiler\Profile;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post("signup",[AuthController::class,'signUp']);
Route::post("signin",[AuthController::class,'signIn']);


Route::middleware('auth:sanctum')->group( function () {
    /**routes for users logged in */
    Route::get("enterprise/{user_id}",[EnterpriseController::class,'show']);
    Route::get("products/{user_id}",[EnterpriseController::class,'products']);
    Route::get("services/{user_id}",[EnterpriseController::class,'services']);
    Route::get("clients/{user_id}",[EnterpriseController::class,'clients']);
    Route::get("sales/{user_id}",[EnterpriseController::class,'sales']);
    Route::get("records/{user_id}",[EnterpriseController::class,'records']);


    /**enterprise options */
    Route::post('enterprise/update/photo',[EnterpriseController::class,'updateLogo']);
    Route::post('enterprise/save',[EnterpriseController::class,'updateEnterprise']);

    /**products */
    Route::post("product/show",[ProductController::class,"show"]);
    Route::post("product/add",[ProductController::class,"add"]);
    Route::post("product/delete",[ProductController::class,'delete']);
    Route::post('product/edit',[ProductController::class,'edit']);


    /**services */
    Route::post("service/show",[ServiceController::class,"show"]);
    Route::post("service/add",[ServiceController::class,"add"]);
    Route::post("service/delete",[ServiceController::class,'delete']);
    Route::post('service/edit',[ServiceController::class,'edit']);

    /**clients */
    Route::post("client/show",[ClientController::class,"show"]);
    Route::post("client/add",[ClientController::class,"add"]);
    Route::post("client/delete",[ClientController::class,'delete']);
    Route::post('client/edit',[ClientController::class,'edit']);

    /**for sales */

    Route::post("sale/show",[SaleController::class,"show"]);
    Route::post("sale/add",[SaleController::class,"add"]);
    Route::post("sale/delete",[SaleController::class,'delete']);
    Route::post('sale/edit',[SaleController::class,'edit']);


    /**for services */
    Route::post("record/show",[RecordController::class,"show"]);
    Route::post("record/add",[RecordController::class,"add"]);
    Route::post("record/delete",[RecordController::class,'delete']);
    Route::post('record/edit',[RecordController::class,'edit']);

    /**invoice and ticket */

    Route::post("generate/invoice/ticket",[InvoiceController::class,'generate']);
    Route::post("generate/copy/invoice/ticket",[InvoiceController::class,"rebuildGenerate"]);

    /**statistics */
    Route::post("statistics",[StatisticController::class,"show"]);

    /**profile */
    Route::post("update/profile",[ProfileController::class,"updateProfile"]);
    Route::post("password/change",[ProfileController::class,"changePassword"]);
    Route::post("profile/update/photo",[ProfileController::class,"updatePhoto"]);
    Route::get("profile/{user_id}",[ProfileController::class,"profile"]);


    Route::post('logout', [AuthController::class, 'logout']);
});
