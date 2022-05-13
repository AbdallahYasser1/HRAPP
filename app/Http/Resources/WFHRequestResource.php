<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WFHRequestResource extends JsonResource
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
            "request_id"=>$this->id,
            "wfh_id"=>$this->requestable_id,
            "requestable_type"=>$this->requestable_type,
            'user_id'=>$this->user_id,
            'start_date'=>$this->start_date,
            'end_date'=>$this->end_date,
            'created_at'=>$this->created_at,


        ];
    }
}
