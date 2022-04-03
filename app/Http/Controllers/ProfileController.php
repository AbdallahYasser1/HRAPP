<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhotoRequest;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
class ProfileController extends ApiController
{
    public function store(Request $request)
    {
        $profile = Profile::create([
            'user_id' => $request['user_id'],
            'department_id' => $request['department_id'],
            'job__titles_id'=>$request['job__titles_id']
        ]);
        return $this->showCustom($profile, 201);
    }
    public function storeDefaultPhoto($id){
        $profile=Profile::where('user_id',$id)->first();
        //when choose default image path var will be empty
        $path='';
        if($profile===null){
            return $this->errorResponse("profile not found",404);
        }else{
            $profile->update([
                'image' => $path
            ]);
            return $this->showCustom($profile, 200);
        }
    }
    public function storePhoto(PhotoRequest $request){
        if($request->hasFile('photo')){
            $path=$request->file('photo')->store('public/images');
            //if(Storage::disk('images')->exists($path)){
                $userProfile=Profile::where('user_id','=',Auth::id());
                $userProfile->update([
                    'image' => str_replace("public","",$path)
                ]);
                return $this->showCustom("image saved", 200);
           // }else{
             //   return $this->errorResponse("image was not uploaded", 400);           
           // }
            // http://127.0.0.1:8000/public/images/lsz93guH03mu1x05PFqm7Pb3Jzj3whCRlr1bGAqA.jpg
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
    public function getprofile(){
        return User::find(Auth::id())->profile;
    }

}
