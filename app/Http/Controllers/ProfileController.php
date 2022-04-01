<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
class ProfileController extends ApiController
{
    public function store(Request $request)
    {
        $profile = Profile::create([
            'user_id' => $request['user_id'],
            'department_id' => $request['department_id']
        ]);
        return $this->showCustom($profile, 201);
    }
    public function storeDefaultPhoto($id){
        $profile=Profile::where('user_id',$id)->first();
        $path='';
        if($profile===null){
            return $this->errorResponse("profile not found",404);
        }else{
            $profile->image=$path;
            return $this->showCustom($profile, 200);
        }
    }
    public function storePhoto(Request $request){
        if($request->hasFile('photo')){
            $path=$request->file('photo')->store('public/images');
            if(Storage::disk('images')->exists($path)){
                $userProfile=Profile::where('user_id','=',Auth::id());
                $userProfile->image=$path;
                return $this->showCustom($userProfile, 200);
            }else{
                return $this->errorResponse("image was not uploaded", 400);           
            }
            
        }
    }
    public function viewUserProfile($id){
        $profile=Profile::where('user_id',$id)->first();
        if($profile===null){
            return $this->errorResponse("profile not found",404);
        }else{
            return $this->showOne($profile,200);
        }
    }
    public function approvePhoto(Request $request,$id){
        $profile=Profile::where('user_id',$id)->first();
        if($profile===null){
            return $this->errorResponse("profile not found",404);
        }else{
            $profile->update([
                'image_approved' => $request['image_approved']
            ]);
            return $this->showOne($profile,200);
        }
    } 

}
