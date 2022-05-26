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
            'job_title' => $this->profile->job_title->job_name,
            'phone' => $this->phone,
            'birthdate'=>$this->birthdate,
            'first_time_login'=>$this->first_time_login==1?true:false,
            'status'=>$this->status,
            'can_wfh'=>$this->can_wfh==1?true:false,
            'roles'=>$this->roles->pluck('name')[0],
            'has_subordinates'=>$this->GetSupervisedCount()!=0?true:false,
            'supervisor'=>$this->supervisor==null?-1:$this->supervisor,
            'active_request_id'=>$this->when( $this->GetActiveMission()->isNotEmpty(),$this->GetActiveMission()[0]->id??'not found'),
           'active_requestable'=>$this->when( $this->GetActiveMission()->isNotEmpty(),$this->GetActiveMission()[0]->requestable??'not found')

        ];
    }
}
