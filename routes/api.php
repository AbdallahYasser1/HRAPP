<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\ProfileController;
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
use App\Http\Controllers\MissionController;
use App\Http\Controllers\MissionUpdatesController;
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
Route::middleware(['auth:sanctum', 'abilities:firstlogin'])->group(function () {
    Route::patch('/resetpassword', [AuthController::class, 'reset_password']);
});
//Application access middleware
Route::middleware(['auth:sanctum', 'abilities:application'])->group(function () {
    //Get Auth User Data
    Route::get('/user', function (Request $request) {
        return $request->user();
    });


    Route::post('/wfh', [WfhController::class, 'store']);
    Route::get('/EmployeeLog', [UserController::class, 'ViewAllRequests']);
    Route::get('/wfh', [WfhController::class, 'showAllWfhRequests']);
    Route::put('/wfh/{id}', [WfhController::class, 'update']);
    Route::get('/wfh/{wfh}', [WfhController::class, 'showWfhRequest']);
    Route::delete('/wfh/{id}', [WfhController::class, 'destroy']);
    Route::post('/mission', [MissionController::class, 'store']);//ok
    Route::get('/mission/{mission}', [MissionController::class, 'showMissionRequest']);//ok
    Route::get('/mission', [MissionController::class, 'showAllMissionRequests']);//ok
    Route::delete('/mission/{id}', [MissionController::class, 'destroy']);//ok
    Route::put('/mission/{id}', [MissionController::class, 'update']);//ok
    Route::put('/mission/updateUser/{id}', [MissionController::class, 'updateDate']);
    Route::post('/missionUpdate', [MissionUpdatesController::class, 'store']);
    Route::delete('/missionUpdate/{id}', [MissionUpdatesController::class, 'destroy']);
    Route::get('/missionUpdate/{id}', [MissionUpdatesController::class, 'show']);
    
    Route::get('/Holidays/{id}', [HolidayController::class, 'show']);
    Route::get('/Holidays/ofMonth/{month}', [HolidayController::class, 'getAllHolidaysOfMonth']);
    Route::post('department', [DepartmentController::class, 'store']);
    Route::get('department/{department}', [DepartmentController::class, 'showJobTitles']);
    Route::post('jobtitle', [JobTitleController::class, 'store']);

    Route::get('supervised', [SupervisorController::class, 'showSupervisedUsers']);
    Route::get('supervisor/requests', [SupervisorController::class, 'showSupervisedUsersPendingRequests']);
    Route::put('supervisor/requests/{id}', [SupervisorController::class, 'supervisorApproveRequest']);
    Route::get('department/users/{department}', [DepartmentController::class, 'getUsersOfDepartment']);

    Route::post('profile', [ProfileController::class, 'store']);
    Route::get('profile', [ProfileController::class, 'getprofile']);
    //when play with this route specifc the data will be "form-data"->(it avialbe in postman) not json
    Route::post('profile/photo', [ProfileController::class, 'storePhoto']);
    Route::put('profile/photoDefault', [ProfileController::class, 'storeDefaultPhoto']);
    

    Route::middleware(['role:Admin'])->delete('profile/{user}', [ProfileController::class, 'destroy']);
    Route::middleware(['role:Admin'])->get('/admin/wfh', [WfhAdminController::class, 'showAllWFHRequestes']);
    Route::middleware(['auth:sanctum', 'role:Admin'])->delete('/Users/{id}', [UserController::class, 'destroy']);
    Route::middleware(['auth:sanctum', 'role:Admin'])->put('/supervisor', [SupervisorController::class, 'makeUserSupervised']);

    Route::middleware(['auth:sanctum', 'role:Admin|HR'])->group(function () {
        //Create New Account
        Route::post('/register', [UserController::class, 'store']);
        Route::put('profile/photoApprove/{id}', [ProfileController::class, 'approvePhoto']);
        //Shift
        Route::get('Shifts/GetUsersShift/{id}', [ShiftController::class, 'getUsersOfShift']);
        Route::get('Shifts/UpdateUserShift/{id}', [ShiftController::class, 'updateUserShift']);
        Route::get('Shifts/GetUserShift/{id}', [ShiftController::class, 'getUserShiftById']);
        Route::apiResource('Users', UserController::class)->only('index', 'show', 'update');
        Route::apiResource('WFH', WfhAdminController::class)->only('destroy', 'update');
        Route::apiResources([
            'Holidays' => HolidayController::class,
            'Shifts' => ShiftController::class
        ]);
        Route::patch('users/{user}', [UserController::class, 'update']);
        Route::delete('users/{user}', [UserController::class, 'destroy']);
        Route::get('users/{user}', [UserController::class, 'show']);
        Route::get('users', [UserController::class, 'index']);
        Route::get('user/requests', [UserController::class, 'ViewAllRequests']);
        Route::get('profile/{id}', [ProfileController::class, 'viewUserProfile']);

    });
}); // end of Application access
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
