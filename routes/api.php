<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\JobTitleController;
use App\Http\Controllers\Salary\SalaryAdjustmentController;
use App\Http\Controllers\Salary\SalaryAdjustmentTypeController;
use App\Http\Controllers\Salary\SalarySlipControl;
use App\Http\Controllers\salary\SalarySlipController;
use App\Http\Controllers\Salary\SalarySlipController2;
use App\Http\Controllers\Salary\TermSlipController;
use App\Http\Controllers\Salary\SalaryTermController;
use App\Http\Controllers\Salary\SlipAdjustmentController;
use App\Http\Controllers\Salary\UserSlipController;
use App\Http\Controllers\Salary\UserTermController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WfhAdminController;
use App\Http\Controllers\WfhController;
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
    Route::post('/register', [UserController::class, 'store']);

});
Route::middleware(['auth:sanctum','abilities:firstlogin'])->group( function () {
    Route::patch('/resetpassword', [AuthController::class, 'reset_password']);
});
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
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

});

Route::middleware(['auth:sanctum','role:Admin'])->delete('/Users/{id}',[UserController::class,'destroy']);

Route::middleware(['auth:sanctum','role:Admin|HR'])->group( function () {
    Route::get('Shifts/GetUsersShift/{id}',[ShiftController::class,'getUsersOfShift']);
    Route::get('Shifts/UpdateUserShift/{id}',[ShiftController::class,'updateUserShift']);
    Route::get('Shifts/GetUserShift/{id}',[ShiftController::class,'getUserShiftById']);
    Route::apiResource('Users', UserController::class)->only('index','show','update');
    Route::apiResource('WFH', WfhAdminController::class)->only('destroy','update');
    Route::apiResources([
        'Holidays' => HolidayController::class,
        'Shifts' =>ShiftController::class
    ]);



});


// Salary
Route::resource('slips', SalarySlipController2::class, );
Route::resource('slips.adjustments', SlipAdjustmentController::class, );

Route::resource('salaryTerms', SalaryTermController::class, );
Route::resource('salaryTerms.slips', TermSlipController::class, );


Route::resource('salaryAdjustments', SalaryAdjustmentController::class, );
Route::resource('salaryAdjustmentTypes', SalaryAdjustmentTypeController::class, );


Route::resource('users.slips', UserSlipController::class, );
Route::resource('users.terms', UserTermController::class, );
Route::get('users/{id}/lastSlip', [UserSlipController::class, 'lastSlip']);
Route::put('users/{id}/lastSlip', [UserSlipController::class, 'updateLastSlip']);


// Salary

//});
