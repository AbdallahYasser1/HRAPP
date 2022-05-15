<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VacationdayResource extends JsonResource
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
            'scheduled'=>$this->vacationday->scheduled,
            'unscheduled'=>$this->vacationday->unscheduled,
            'email'=>$this->email,
            'name'=>$this->name
        ];
    }
}
