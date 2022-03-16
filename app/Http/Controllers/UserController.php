<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
        $users = User::all();
        if($users===null){
            return $this->errorResponse("users not found", 404);
        }else{
            return new UserCollection($users->paginate());
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
    public function show($id)
    {
        $user = User::where('id', $id)->first();
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
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::find($id);
        if ($user === null) {
            return $this->errorResponse("user not found", 404);
        } else {
            $user->update([
                'name' => $request['name'],
                'birthdate' => $request['birthdate'],
                'phone' => $request['phone'],
                'email' => $request['email'],
                'phone' => $request['phone'],
            ]);
            return $this->showOne($user, 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user === null) {
            return $this->errorResponse("user not found", 404);
        } else {
            $user->delete();
            return $this->showCustom(['user deleted'], 200);
        }
    }

    public function acceptPhoto(){
        
    }
}
