<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WfhAdminController;
use App\Http\Controllers\WfhController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\JobTitleController;
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
//Login
Route::post('login', [AuthController::class, 'login']);
Route::middleware(['auth:sanctum','abilities:firstlogin'])->group( function () {
    Route::patch('/resetpassword', [AuthController::class, 'reset_password']);
});
//Application access middleware
Route::middleware(['auth:sanctum','abilities:application'])->group( function () {
//Get Auth User Data
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });


    Route::post('/wfh',[WfhController::class,'store']);
    Route::put('/wfh/{id}',[WfhController::class,'update']);
    Route::get('/wfh/{wfh}',[WfhController::class,'showWfhRequest']);
    Route::get('/wfh',[WfhController::class,'showAllWfhRequests']);
    Route::delete('/wfh/{id}',[WfhController::class,'destroy']);
    Route::get('/Holidays/{id}',[HolidayController::class,'show']);
    Route::get('/Holidays/ofMonth/{month}',[HolidayController::class,'getAllHolidaysOfMonth']);
    Route::post('department',[DepartmentController::class,'store']);
    Route::get('department/{department}',[DepartmentController::class,'showJobTitles']);
    Route::post('jobtitle',[JobTitleController::class,'store']);

    Route::get('supervised',[SupervisorController::class,'showSupervisedUsers']);
    Route::get('supervisor/requests',[SupervisorController::class,'showSupervisedUsersPendingRequests']);
    Route::get('department/users/{department}',[DepartmentController::class,'getUsersOfDepartment']);

Route::post('profile',[ProfileController::class,'store']);

Route::middleware(['auth:sanctum','role:Admin'])->delete('/Users/{id}',[UserController::class,'destroy']);

Route::middleware(['auth:sanctum','role:Admin|HR'])->group( function () {
    //Create New Account
    Route::post('/register', [UserController::class, 'store']);
    //Shift
    Route::get('Shifts/GetUsersShift/{id}',[ShiftController::class,'getUsersOfShift']);
    Route::get('Shifts/UpdateUserShift/{id}',[ShiftController::class,'updateUserShift']);
    Route::get('Shifts/GetUserShift/{id}',[ShiftController::class,'getUserShiftById']);
    Route::apiResource('Users', UserController::class)->only('index','show','update');
    Route::apiResource('WFH', WfhAdminController::class)->only('destroy','update');
    Route::apiResources([
        'Holidays' => HolidayController::class,
        'Shifts' =>ShiftController::class
    ]);
Route::patch('users/{user}',[UserController::class,'update']);
Route::delete('users/{user}',[UserController::class,'destroy']);
Route::get('users/{user}',[UserController::class,'show']);
Route::get('users',[UserController::class,'index']);

});


}); // end of Application access
