<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketRS extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->special_validity();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'cost' => $this->cost,
            'available' => $this->available,
        ];
    }
}
