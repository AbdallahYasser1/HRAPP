<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'profile_id'=>$this->profile->id,
            'department'=>$this->profile->department->name,
            'jobTiltle'=>$this->profile->job_title->job_name,
            'image_approved'=>$this->profile->image_approved,
            'image'=>$this->profile->image,
            'name'=>$this->name
        ];
    }
}
