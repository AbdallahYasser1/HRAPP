<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'birthdate'=>$this->birthdate,
            'first_time_login'=>$this->first_time_login==1?true:false,
            'status'=>$this->status,
            'can_wfh'=>$this->can_wfh==1?true:false,
            'roles'=>$this->roles->pluck('name')[0],

        ];
    }
}
