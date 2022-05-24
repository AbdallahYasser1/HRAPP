<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthResource;
use App\Models\Profile;
use App\Models\Requestdb;
use App\Models\Salary\SalaryTerm;
use App\Models\Vacationday;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        if($users===null){
            return $this->errorResponse("Users are not existed", 404);
        }else{
            return new UserCollection($users);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        if ($request['role'] == 'Admin' && !Auth::user()->hasPermissionTo('create_account_Admin')) return  $this->errorResponse(' The authenticated user is not permitted to perform the requested operation.', 403);
        $hashed_random_password = Str::random(10);
        $user = User::create([
            'id' => $request['id'],
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'birthdate' => $request['birthdate'],
            'shift_id' => $request['shift_id'],
            'password' => $hashed_random_password,
            'can_wfh'=>$request['can_wfh'],
            'supervisor'=>$request['supervisor'],
        ]);
        $vacationday=Vacationday::create( ['user_id' => $request['id'],
            'scheduled' => $request['scheduled'],
            'unscheduled'=>$request['unscheduled']]);
        $profile = Profile::create([
            'user_id' => $request['id'],
            'department_id' => $request['department_id'],
            'job__title_id'=>$request['job__title_id']
        ]);
        $salary_term=SalaryTerm::create([
            'user_id'=>$request['id'],
            'salary_agreed'=>$request['salary']
        ]);
        $user->assignRole($request['role']);
        $response = ['user' => new AuthResource($user), 'password' => $hashed_random_password];
        return $this->showCustom($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        if ($user === null) {
            return $this->errorResponse("user not found", 404);
        } else {
            return $this->showOne($user, 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if ($request['role'] == 'Admin' && !Auth::user()->hasPermissionTo('create_account_Admin')) return  $this->errorResponse(' The authenticated user is not permitted to perform the requested operation.', 403);
        if ($user === null) {
            return $this->errorResponse("user not found", 404);
        } else {
$User_Request= new Request([
    'name' => $request['name'] ==null?$user->name : $request['name'],
    'email' => $request['email'] ==null?$user->email : $request['email'],
    'phone' => $request['phone'] ==null?$user->phone : $request['phone'],
    'birthdate' => $request['birthdate'] ==null?$user->birthdate : $request['birthdate'],
    'shift_id' => $request['shift_id'] ==null?$user->shift_id : $request['shift_id'],
    'password' => $request['password'] ==null?$user->password : $request['password'],
    'can_wfh'=>$request['can_wfh'] ==null?$user->can_wfh : $request['can_wfh'],
    'supervisor'=>$request['supervisor'] ==null?$user->supervisor : $request['supervisor']
]);
$Profile_Request=new Request([
    'department_id' => $request['department_id']==null?$user->profile()->department_id : $request['department_id'],
    'job__title_id'=>$request['job__title_id']==null?$user->profile()->job__title_id : $request['job__title_id']
]);
$Salary_Request=new Request(['salary_agreed'=>$request['salary']==null?$user->salaryTerm():$request['salary']]);
            $user->update($User_Request->all());
            $user->profile()->update($Profile_Request->all());
            $user->salaryTerm()->update($Salary_Request);
            //$user->assignRole($request['role']);
            $response=['User'=>$user,'profile'=>$user->profile(),'salary'=>$user->salaryTerm()];
            return $this->showCustom($response, 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if($user->id==Auth::id() )  return $this->errorResponse("Can't Delete your account", 403);
        if ($user === null) {
            return $this->errorResponse("user not found", 404);
        } else {
            $user->delete();
            return $this->showCustom(['user deleted'], 200);
        }
    }

    public function ViewAllRequests(Request $request){
        $status=$request->query('status');
        if($status===null){  $requestes=Requestdb::with(['requestable'])
            ->join('users','requestdbs.user_id','users.id')
            ->where('users.id','=',Auth::id())
            ->get(); }
        else{
            $requestes=Requestdb::with(['requestable'])
                ->join('users','requestdbs.user_id','users.id')
                ->where('users.id','=',Auth::id())
                ->where('requestdbs.status','=',$status)
                ->get();

        }
        return $this->showCustom($requestes,200);
    }
    function showAllUsersAdmin(Request $request){
        return $this->showAll(User::all(),200);
    }
    function UserStatus(){
        return $this->showCustom(Auth::user()->status,200);
    }
}
