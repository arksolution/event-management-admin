<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{

    public $timestamps = false;
    protected $guarded = [];
    function session_registration(){
        return $this->hasMany(Session_registration::class , 'registration_id');
    }

    function attendee(){
        return $this->hasMany(Attendee::class, 'attendee_id');
    }

    function ticket(){
        return $this->belongsTo(Event_ticket::class, 'ticket_id');
    }
}
