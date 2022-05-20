<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TagsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $tags = array();
        if($this->Tags){
            foreach(explode(',',$this->Tags) as $tag){
                $TagColor = GetTabInfo($tag);
                $tags[] =  $TagColor['TagColor'];
            }
        }
        return $tags;

    }
}
