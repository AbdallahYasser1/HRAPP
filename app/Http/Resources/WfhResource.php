<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WfhResource extends JsonResource
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
            'wfh_id'=>$this->id,
            'request_id'=>$this->requests[0]->id,
            'user_id'=>$this->requests[0]->user_id,
            'date'=>$this->requests[0]->start_date,
            'type'=>$this->requests[0]->requestable_type,
            'status'=>$this->requests[0]->status,
            'approved'=>$this->requests[0]->is_approved==0?false:true,
            'created_at'=>$this->requests[0]->created_at,


        ];
    }
}
