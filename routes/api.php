<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HolidayController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['auth:sanctum','abilities:application'])->group( function () {
    Route::post('/register', [AuthController::class, 'Create_Account']);

});
Route::middleware(['auth:sanctum','abilities:firstlogin'])->group( function () {
    Route::patch('/resetpassword', [AuthController::class, 'reset_password']);
});
Route::post('login', [AuthController::class, 'login']);
Route::group(['middleware' => ['role:super-admin']], function () {
    //
});
Route::middleware('auth:sanctum')->get('/Holidays/{id}',[HolidayController::class,'show']);
Route::middleware(['auth:sanctum','role:Admin|HR'])->group( function () {
    Route::apiResources([
        'Holidays' => HolidayController::class,
    ]);
});
Route::post('/test', [AuthController::class, 'Test']);
