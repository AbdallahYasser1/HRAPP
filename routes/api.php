<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobTitleController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\Salary\SalaryAdjustmentController;
use App\Http\Controllers\Salary\SalaryAdjustmentTypeController;
use App\Http\Controllers\Salary\SalarySlipController2;
use App\Http\Controllers\Salary\TermSlipController;
use App\Http\Controllers\Salary\SalaryTermController;
use App\Http\Controllers\Salary\SlipAdjustmentController;
use App\Http\Controllers\Salary\UserSlipController;
use App\Http\Controllers\Salary\UserTermController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskEmployeeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VacationController;
use App\Http\Controllers\WfhAdminController;
use App\Http\Controllers\WfhController;
use App\Http\Controllers\MissionController;
use App\Http\Controllers\MissionUpdatesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Attendance\AttendanceController;
use App\Http\Controllers\Attendance\UserAttendanceController;
use App\Http\Controllers\Salary\UserSlipAdjustmentController;
use App\Http\Controllers\Salary\CalculateNetSalaryController;
use App\Http\Controllers\VacationdayController;
use App\Http\Resources\AuthResource;
use App\Http\Controllers\Attendance\UserAttendController;
use App\Http\Controllers\Absence\AbsenceController;
use App\Http\Controllers\Absence\UserAbsenceController;
use App\Http\Controllers\GetCompanyStstistics;





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
        return new AuthResource($request->user());
    });
    Route::get('vacationdayuser', [VacationdayController::class, 'showVacationdayAuth']);
    //Requests
    Route::get('/requests', [RequestController::class, 'ShowAllUserRequestsFilter']);

    //WFH
    Route::post('/wfh', [WfhController::class, 'store']);
    Route::get('/EmployeeLog', [UserController::class, 'ViewAllRequests']);
    Route::get('/wfh', [WfhController::class, 'showAllWfhRequests']);
    Route::put('/wfh/{wfh}', [WfhController::class, 'update']);
    Route::get('/wfh/{wfh}', [WfhController::class, 'showWfhRequest']);
    Route::delete('/wfh/{id}', [WfhController::class, 'destroy']);
    //Mission
    Route::post('/mission', [MissionController::class, 'store']); //ok
    Route::get('/mission/{mission}', [MissionController::class, 'showMissionRequest']); //ok
    Route::get('/mission', [MissionController::class, 'showAllMissionRequests']); //ok
    Route::delete('/mission/{id}', [MissionController::class, 'destroy']); //ok
    Route::put('/mission/{id}', [MissionController::class, 'update']); //ok
    Route::put('/mission/updateUser/{id}', [MissionController::class, 'updateDate']); //ok
    Route::post('/missionUpdate/{mission}', [MissionUpdatesController::class, 'store']); //ok
    Route::delete('/missionUpdate/{id}', [MissionUpdatesController::class, 'destroy']); //ok
    Route::get('/missionUpdate/{id}', [MissionUpdatesController::class, 'show']); //ok
    //Tasks
    Route::post('task', [TaskController::class, 'store']);
    Route::get('tasks', [TaskController::class, 'ShowAllTasks']);
    Route::put('tasks/{task}', [TaskController::class, 'CancelTask']);
    Route::get('tasks/{task}', [TaskController::class, 'ShowTask']);
    Route::delete('tasks/{task}/employees', [TaskEmployeeController::class, 'DeleteEmployee']);
    Route::post('tasks/{task}/employees', [TaskEmployeeController::class, 'AddEmployee']);
    Route::get('tasks/{task}/employees', [TaskEmployeeController::class, 'ShowEmployees']);
    Route::put('tasks/{task}/employee', [TaskEmployeeController::class, 'MarkTheTask']);

    //Holidays
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
    //Vacation
    Route::post('/{absence}/vacation', [VacationController::class, 'UnscheduledVacation']);
    Route::post('/vacation', [VacationController::class, 'ScheduledVacation']);
    Route::get('/vacation/{vacation}', [VacationController::class, 'ShowVacationRequest']);
    Route::get('/vacationbalance', [VacationController::class, 'RemainingVacationBalance']);

    Route::middleware(['role:Admin'])->delete('profile/{user}', [ProfileController::class, 'destroy']);
    Route::middleware(['role:Admin'])->get('/admin/wfh', [WfhAdminController::class, 'showAllWFHRequestes']);
    Route::middleware(['role:Admin'])->apiResource('Config',ConfigController::class);
    Route::middleware(['role:Admin'])->get('/admin/mission', [MissionController::class, 'showAllMissionRequestsAdmin']);
    Route::middleware(['auth:sanctum', 'role:Admin'])->delete('/Users/{id}', [UserController::class, 'destroy']);
    Route::middleware(['auth:sanctum', 'role:Admin'])->put('/supervisor', [SupervisorController::class, 'makeUserSupervised']);

    Route::middleware(['auth:sanctum', 'role:Admin|HR'])->group(function () {
        Route::get('/getCompanyStstistics', GetCompanyStstistics::class);
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
            'Shifts' => ShiftController::class,
            'Vacationday' => VacationdayController::class
        ]);
        Route::patch('users/{user}', [UserController::class, 'update']);
        Route::delete('users/{user}', [UserController::class, 'destroy']);
        Route::get('users/{user}', [UserController::class, 'show']);
        Route::get('users', [UserController::class, 'index']);
        Route::patch('user/changepassword', [AuthController::class, 'reset_password']);
        Route::get('user/requests', [UserController::class, 'ViewAllRequests']);
        Route::get('user/status', [UserController::class, 'UserStatus']);
        Route::get('profile/{id}', [ProfileController::class, 'viewUserProfile']);
    });
}); // end of Application access
Route::middleware(['auth:sanctum', 'role:Admin|HR'])->group(function () {
    Route::get('admin/requests', [RequestController::class, 'ShowAllRequestsAdmin']);

    Route::get('Shifts/GetUsersShift/{id}', [ShiftController::class, 'getUsersOfShift']);
    Route::get('Shifts/UpdateUserShift/{id}', [ShiftController::class, 'updateUserShift']);
    Route::get('Shifts/GetUserShift/{id}', [ShiftController::class, 'getUserShiftById']);
    Route::apiResource('Users', UserController::class)->only('index', 'show', 'update');
    Route::apiResource('WFH', WfhAdminController::class)->only('destroy', 'update');
    Route::apiResources([
        'Holidays' => HolidayController::class,
        'Shifts' => ShiftController::class
    ]);
});
Route::middleware(['auth:sanctum', 'role:Admin|HR|Accountant'])->group(function () {

    Route::resource('slips', SalarySlipController2::class,);
    Route::resource('slips.adjustments', SlipAdjustmentController::class,);

    Route::get('users/{user}/slips', [UserSlipController::class, 'getUserSlips']);

    Route::resource('salaryTerms', SalaryTermController::class,);
    Route::resource('salaryTerms.slips', TermSlipController::class,);
    Route::resource('users.salaryTerms', UserTermController ::class, ['only' => ['store', 'destroy']]);

    Route::resource('salaryAdjustments', SalaryAdjustmentController::class,);
    Route::resource('salaryAdjustmentTypes', SalaryAdjustmentTypeController::class,);

    Route::get('users/{id}/lastSlip', [UserSlipController::class, 'lastSlip']);
    Route::put('users/{id}/lastSlip', [UserSlipController::class, 'updateLastSlip']);
    Route::delete('users/{id}/lastSlip', [UserSlipController::class, 'destroyLastSlip']);
    Route::resource('users.slips.adjustments', UserSlipAdjustmentController::class,);
    Route::get('users/{id}/lastSlip/adjustments', [UserSlipAdjustmentController::class, 'lastSlipAdjustments']);
    Route::resource('attendances', AttendanceController::class,);
    Route::resource('users.attendances', UserAttendanceController::class,);
    Route::resource('absences', AbsenceController::class,);
    Route::resource('users.absences', UserAbsenceController::class,);
    Route::post('users/{id}/slips', [UserSlipController::class, 'store']);

    Route::put('slips/{slip}/calc', [CalculateNetSalaryController::class, 'calcSlip']);
    Route::put('users/{user}/slips/{slip}/calc', [CalculateNetSalaryController::class, 'calculateUserSlip']);
});

Route::middleware(['auth:sanctum', 'role:Admin|HR|Accountant|Normal'])->group(function () {
    Route::get('/user/slips', [UserSlipController::class, 'index']);
    Route::get('/user/slips/{slip}', [UserSlipController::class, 'show']);
    Route::get('/user/slipByDate', [UserSlipController::class, 'getSlipByMonth']);



    Route::get('/user/term', [UserTermController::class, 'index']);

    Route::get('user/slips/{slip}/adjustments', [UserSlipAdjustmentController::class, 'index']);
    Route::get('user/slips/{slip}/adjustments/{adjustment}', [UserSlipAdjustmentController::class, 'show']);


    Route::get('users/{id}/lastSlip', [UserSlipController::class, 'lastSlip']);
    Route::get('users/{id}/lastSlip/adjustments', [UserSlipAdjustmentController::class, 'lastSlipAdjustments']);

    Route::get('/user/attendances', [UserAttendanceController::class,  'index']);
    Route::get('/user/attendances/{attendance}', [UserAttendanceController::class,  'show']);

    Route::put('user/attend', [UserAttendController::class, 'attendEmployee']);

    Route::get('user/absences', [UserAbsenceController::class, 'index']);

    Route::put('user/slips/{slip}/calc', [CalculateNetSalaryController::class, 'calculateMySlip']);
    Route::put('user/lastSlip/calc', [CalculateNetSalaryController::class, 'calcMyLastSlip']);

});

