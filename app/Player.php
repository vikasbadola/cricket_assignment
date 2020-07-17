<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'teamId', 'firstName', 'lastName','imageUri','jerseyNo','country'
    ];
    
    public function team()
    {
        return $this->belongsTo('App\Team','teamId','teamId');
    }
}
