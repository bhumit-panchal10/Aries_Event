<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\Api\CityApiController;
use App\Http\Controllers\Api\IndustryApiController;
use App\Http\Controllers\Api\DepartmentApiController;
use App\Http\Controllers\Api\ExpoApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\ExpoAssignToUserApiController;
use App\Http\Controllers\Api\VisitorApiController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Api\ExhibitorContactController;
use App\Http\Controllers\Api\IndustryCategoryController;
use App\Http\Controllers\Api\IndustrySubCategoryController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
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
Route::post('/Industrywise/Expo', [IndustryApiController::class, 'IndustrywiseExpo']);


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
Route::post('/user/change-password', [UserApiController::class, 'changePassword']);
Route::post('/user/change-status', [UserApiController::class, 'changeStatus']);
Route::post('/User/login', [UserApiController::class, 'Userlogin']);
Route::post('/Assign/Expolist', [UserApiController::class, 'AssignExpolist']);
Route::post('/user/changepassword', [UserApiController::class, 'user_changePassword']);
Route::post('/user/profile', [UserApiController::class, 'userprofile']);
Route::post('/user/profile/update', [UserApiController::class, 'profileupdate']);
Route::post('/user/logout', [UserApiController::class, 'logout']);

Route::post('/ExpoAssign/UserAdd', [ExpoAssignToUserApiController::class, 'ExpoUserAdd']);
Route::post('/ExpoAssign/UserList', [ExpoAssignToUserApiController::class, 'ExpoUserList']);
Route::post('/ExpoAssign/UserDelete', [ExpoAssignToUserApiController::class, 'ExpoUserDelete']);

Route::post('/Visitor/Add', [VisitorApiController::class, 'visitoradd']);
Route::post('/visitor/by-mobile', [VisitorApiController::class, 'getByMobile']);
Route::post('/Visitor/list', [VisitorApiController::class, 'visitorlist']);
Route::post('/Visitor/show', [VisitorApiController::class, 'visitorshow']);
Route::post('/Visitor/Update', [VisitorApiController::class, 'visitorupdate']);
Route::post('/visitor/check-visitor-by-mobile', [VisitorApiController::class, 'checkVisitorByMobile']);
Route::post('/visitor/store', [VisitorApiController::class, 'visitorstore']);
Route::post('/visitor/today-expected-visitor-count', [VisitorApiController::class, 'expectedVisitorCount']);

Route::post('/visitor/user/count', [VisitorApiController::class, 'userVisitorCount']);
Route::post('/Expowise/count', [VisitorApiController::class, 'ExpowiseCount']);

Route::post('/Visitordata/Upload', [VisitorApiController::class, 'VisitordataUpload']);
Route::post('/admin_visitor_list', [VisitorApiController::class, 'adminVisitorList']);
Route::post('/admin_visitors_export', [VisitorApiController::class, 'exportVisitors']);

Route::prefix('exhibitors')->group(function () {
    Route::post('/store', [ExhibitorContactController::class, 'store']);
    Route::post('/', [ExhibitorContactController::class, 'index']);
    Route::post('/show', [ExhibitorContactController::class, 'show']);
    Route::post('/search-by-mobile', [ExhibitorContactController::class, 'searchByMobile']);
    Route::post('/Expowise/count', [ExhibitorContactController::class, 'ExpowiseCount']);
});

Route::prefix('industry-categories')->group(function () {
    Route::post('/', [IndustryCategoryController::class, 'index']);
    Route::post('/store', [IndustryCategoryController::class, 'store']);
    Route::post('/show', [IndustryCategoryController::class, 'show']);
    Route::post('/update', [IndustryCategoryController::class, 'update']);
    Route::post('/delete', [IndustryCategoryController::class, 'destroy']);
});

Route::prefix('industry-subcategories')->group(function () {
    Route::post('/', [IndustrySubCategoryController::class, 'index']);
    Route::post('/store', [IndustrySubCategoryController::class, 'store']);
    Route::post('/show', [IndustrySubCategoryController::class, 'show']);
    Route::post('/update', [IndustrySubCategoryController::class, 'update']);
    Route::post('/delete', [IndustrySubCategoryController::class, 'destroy']);
    
    // Additional routes
    Route::post('/get-by-industry', [IndustrySubCategoryController::class, 'getCategoriesByIndustry']);
    Route::post('/get-by-category', [IndustrySubCategoryController::class, 'getByCategory']);
    //Route::post('/bulk-status-update', [IndustrySubCategoryController::class, 'bulkStatusUpdate']);
});

