<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends ApiController
{
    public function store (Request $request){
$profile=Profile::create([
    'user_id'=>$request['user_id'],
    'department_id'=>$request['department_id'],
    'image'=>$request['image']
]);
return $this->showCustom($profile,201);
    }
}
