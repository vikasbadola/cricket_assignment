<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    
    public function teamA()
    {
        return $this->belongsTo('App\Team','teamA','teamId');
    }
    
    public function teamB()
    {
        return $this->belongsTo('App\Team','teamB','teamId');
    }
    
    public function winner()
    {
        return $this->belongsTo('App\Team','winner','teamId');
    }
}
