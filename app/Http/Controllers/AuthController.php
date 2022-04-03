<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;
use App\Http\Resources\AuthResource;

class AuthController extends ApiController
{
    public function credentials(Request $request)
    {
        return [
            'id'     => $request->id,
            'password'  => $request->password,
        ];
    }
    public function Login(Request $request)
    {
        //check the credentials  Auth::attempt($this->credentials($request))

        if (Auth::attempt($this->credentials($request))) {
            //first time login
            //  $user = User::where('id', $request->id)->first(); // get user
            $user = Auth::loginUsingId($request->id);
            //check if user is active
            if (!$user->active) return response()->json(["message" => "The Account is disabled please contact an admin"], 403);

            if ($user->first_time_login) {
                $token = $user->createToken('first', ['firstlogin'])->plainTextToken;
                $response = ['message' => "First Time login please Change your Password to use application", 'token' => $token];
                return response()->json($response, 200);
            }
            $token = $user->createToken('myapptoken', ['application'])->plainTextToken;
            $response = ['user'=>new AuthResource($user), 'token' => $token];
            return $response;
            //return response()->json($response, 200);
        }
        return response()->json(["message" => "The given data was invalid."], 401);
    }

    public function reset_password(LoginUserRequest   $request)
    {
        //       $request->validate([
        //               'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()->mixedCase()->symbols()],]
        //       );
        $user = Auth::user();
        error_log($user);
        $user->password = bcrypt($request->password);
        $user->first_time_login = false;
        $user->save();
        auth()->user()->tokens()->delete();
        return response()->json(["message" => "Password has changed succesfully please login again with the new password"], 200);
    }
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return response()->json(["message" => "Logout Sucessfully"], 200);
    }
}
 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     *public function Create_Account(StoreUserRequest $request)
     *{
     *   if ($request['role'] == 'Admin' && !Auth::user()->hasPermissionTo('create_account_Admin')) return  $this->errorResponse('This is out limit', 403);
     *  $hashed_random_password = Str::random(10);
     * $user = User::create([
     *      'id' => $request['id'],
     *     'name' => $request['name'],
     *    'email' => $request['email'],
     *    'phone' => $request['phone'],
     *   'birthdate' => $request['birthdate'],
     *     'shift_id' => $request['shift_id'],
     *    'password' => bcrypt($hashed_random_password),

     * ]);
     * $user->assignRole($request['role']);
     * $response = ['user' => $user, 'password' => $hashed_random_password];
     * return $this->showCustom($response, 201);
     * }

    *public function Test(Request $request)
    *{
    *    $authuser = Auth::user();

        *  error_log($user);

        * if(!$authuser->hasPermissionTo('create_account')) return $this->errorResponse('This is out limit',403);
     *   $fields = $request->validate([
      *      'id' => 'required|integer|unique:users,id',
      *      'name' => 'required|string',
       *     'birthdate' => 'required|date',
       *     'phone' => 'required|string|unique:users,phone',
       *     'email' => 'required|string|unique:users,email',
        *    'role' => 'required|string|in:Normal,HR,Admin,Accountant'

        *]);
       * //if($fields['role']=='Admin'&& !$authuser->hasPermissionTo('create_account_Admin')) return  $this->errorResponse('This is out limit',403);
       * $hashed_random_password = Str::random(10);
       * $user = User::create([
        *    'id' => $fields['id'],
        *    'name' => $fields['name'],
        *    'email' => $fields['email'],
        *    'phone' => $fields['phone'],
        *    'birthdate' => $fields['birthdate'],
        *    'password' => bcrypt($hashed_random_password),
        *]);
        *$user->assignRole($fields['role']);
        *$response = ['user' => $user, 'password' => $hashed_random_password];
        *return $this->showCustom($response, 201);
    *}
         */
