<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SlipResource extends JsonResource
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
        "user_id"=>$this->user_id,
        "salary_term_id"=>$this->salary_term_id,
        "user_name"=>$this->user_name,
        "net_salary"=>$this->net_salary,
        "date"=> $this->date,
              "total_adjustment"=>$this->adjustments()->where('amount', '<', 0)->sum('amount'),
              "total_earnings"=>$this->adjustments()->where('amount', '>', 0)->sum('amount'),
              "adjustment"=>$this->adjustments()->where('amount', '<', 0)->get(['amount','title'])->map(function($record){
                return[  'value'=>$record->amount,
                  'title'=>$record->title];
              })     ,
        "earnings"=>$this->adjustments()->where('amount', '>', 0)->get(['amount','title'])->map(function($record){
                return[  'amount'=>$record->amount,
                  'title'=>$record->title];
              })

    ];
    }
}
