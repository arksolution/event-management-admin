<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    function speaker_session(){
        return $this->hasMany(Speaker_session::class, 'speaker_id');
    }
}
