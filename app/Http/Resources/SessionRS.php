<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SessionRS extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $speaker = [];
        foreach ($this->speaker_session as $value){
            array_push($speaker, $value->speaker);
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'speaker' => $speaker,
            'start' => $this->start,
            'end' => $this->end,
            'type' => $this->type,
            'cost' => $this->cost,
        ];
    }
}
