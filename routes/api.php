<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\Api\CityApiController;
use App\Http\Controllers\Api\IndustryApiController;
use App\Http\Controllers\Api\DepartmentApiController;
use Illuminate\Support\Facades\Artisan;

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

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    return 'Cache is cleared';
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/adminlogin', [AdminApiController::class, 'adminlogin']);

Route::post('/CityAdd', [CityApiController::class, 'CityAdd']);
Route::post('/CityList', [CityApiController::class, 'CityList']);
Route::post('/Cityshow', [CityApiController::class, 'Cityshow']);
Route::post('/CityUpdate', [CityApiController::class, 'CityUpdate']);
Route::post('/CityDelete', [CityApiController::class, 'CityDelete']);
Route::post('/statelist', [CityApiController::class, 'statelist']);

Route::post('/IndustryAdd', [IndustryApiController::class, 'IndustryAdd']);
Route::post('/IndustryList', [IndustryApiController::class, 'IndustryList']);
Route::post('/Industryshow', [IndustryApiController::class, 'Industryshow']);
Route::post('/IndustryUpdate', [IndustryApiController::class, 'IndustryUpdate']);
Route::post('/IndustryDelete', [IndustryApiController::class, 'IndustryDelete']);

Route::post('/DepartmentAdd', [DepartmentApiController::class, 'DepartmentAdd']);
Route::post('/DepartList', [DepartmentApiController::class, 'DepartList']);
Route::post('/Departshow', [DepartmentApiController::class, 'Departshow']);
Route::post('/DepartUpdate', [DepartmentApiController::class, 'DepartUpdate']);
Route::post('/DepartDelete', [DepartmentApiController::class, 'DepartDelete']);
