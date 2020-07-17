<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'teamName', 'state', 'logoUri',
    ];
    
    public function teamB()
    {
        return $this->hasMany('App\Match','teamId','teamB');
    }
}
