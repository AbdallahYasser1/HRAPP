<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Requestdb;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use Illuminate\Support\Facades\Auth;
class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate();
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
        if ($request['role'] == 'Admin' && !Auth::user()->hasPermissionTo('create_account_Admin')) return  $this->errorResponse('This is out limit', 403);
        $hashed_random_password = Str::random(10);
        $user = User::create([
            'id' => $request['id'],
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'birthdate' => $request['birthdate'],
            'shift_id' => $request['shift_id'],
            'password' => bcrypt($hashed_random_password),
            'can_wfh'=>$request['can_wfh']

        ]);

        $user->assignRole($request['role']);
        $response = ['user' => $user, 'password' => $hashed_random_password];
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
        if ($user === null) {
            return $this->errorResponse("user not found", 404);
        } else {
            $user->update($request->all());
            return $this->showOne($user, 200);
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

    public function acceptPhoto(){

    }
}
