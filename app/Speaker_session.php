<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Speaker_session extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    function speaker(){
        return $this->belongsTo(Speaker::class, 'speaker_id');
    }

    function session(){
        return $this->belongsTo(Session::class, 'session_id');
    }
}
