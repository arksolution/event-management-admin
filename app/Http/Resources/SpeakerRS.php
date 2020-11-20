<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SpeakerRS extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $sessionJoined = [];

        foreach ($this->speaker_session as $value){
            $data = null;
            $data['id'] = $value->session->id;
            $data['title'] = $value->session->title;
            $data['event_slug'] = $value->session->room->channel->event->slug;
            $data['organizer_slug'] = $value->session->room->channel->event->organizer->slug;
            array_push($sessionJoined, $data);
        }

        $localFileName  = public_path().'\uploads\avatars\\' . $this->avatar;
        $fileData = file_get_contents($localFileName);
        $ImgfileEncode = base64_encode($fileData);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'link' => $this->link,
            'info' => $this->info,
            'session_joined' => $sessionJoined,
            'avatar' => $ImgfileEncode,
        ];
    }
}
