<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RegistrationRS extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $session_ids = [];
        foreach ($this->session_registration as $value)
            array_push($session_ids, $value->session_id);
        return [
            'event'=> new EventRS($this->ticket->event),
            'session_ids' => $session_ids
        ];
    }
}
