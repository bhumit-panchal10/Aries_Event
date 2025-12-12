<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\Api\CityApiController;
use App\Http\Controllers\Api\IndustryApiController;
use App\Http\Controllers\Api\DepartmentApiController;
use App\Http\Controllers\Api\ExpoApiController;
use App\Http\Controllers\Api\UserApiController;
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
Route::post('/admin/profile', [AdminApiController::class, 'profiledetails'])->name('profiledetails');
Route::post('/admin/profile/update', [AdminApiController::class, 'profileUpdate'])->name('profileUpdate');
Route::post('/admin/change/password', [AdminApiController::class, 'change_password'])->name('change_password');
Route::post('/logout', [AdminApiController::class, 'logout']);


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

Route::post('/ExpoAdd', [ExpoApiController::class, 'ExpoAdd']);
Route::post('/ExpoList', [ExpoApiController::class, 'ExpoList']);
Route::post('/Exposhow', [ExpoApiController::class, 'Exposhow']);
Route::post('/ExpoUpdate', [ExpoApiController::class, 'ExpoUpdate']);
Route::post('/ExpoDelete', [ExpoApiController::class, 'ExpoDelete']);
Route::post('/CityByState', [ExpoApiController::class, 'CityByState']);

Route::post('/UserAdd', [UserApiController::class, 'UserAdd']);
Route::post('/UserList', [UserApiController::class, 'UserList']);
Route::post('/Usershow', [UserApiController::class, 'Usershow']);
Route::post('/UserUpdate', [UserApiController::class, 'UserUpdate']);
Route::post('/UserDelete', [UserApiController::class, 'UserDelete']);
