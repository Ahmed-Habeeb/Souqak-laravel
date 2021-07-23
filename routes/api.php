<?php
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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

Route::group([

    'middleware' => 'api',

], function ($router) {


    Route::post('create', [AuthController::class,'create']);
    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', 'AuthController@refresh');
    Route::get('me', [AuthController::class,'me']);
    Route::get('fetchlatest', [ItemController::class,'fetchLatest']);

    Route::group(['prefix'=>'doctor'],function ($router){
        Route::post('add', [DoctorController::class,'addDoctor']);
        Route::post('bycategory', [DoctorController::class,'getDoctorsbyCategory']);

        Route::get('all', [DoctorController::class,'getDoctors']);

    });
    Route::group(['prefix'=>'service'],function ($router){
        Route::post('add', [ServiceController::class,'addService']);
        Route::post('bycategory', [ServiceController::class,'getServicessbyCategory']);

        Route::get('all', [ServiceController::class,'getServices']);

    });
    Route::group(['prefix'=>'items'],function ($router){
        Route::post('add',[ItemController::class,'addItem']);
        Route::post('update',[ItemController::class,'updateItem']);
        Route::post('delete',[ItemController::class,'deleteItem']);

        Route::get('all',[ItemController::class,'fetchAll']);
        Route::post('category',[ItemController::class,'fetchCategory']);
        Route::post('subcategory',[ItemController::class,'fetchSubcategory']);
        Route::post('userid',[ItemController::class,'fetchUserId']);




    });
});
