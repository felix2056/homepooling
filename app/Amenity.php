<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    public function properties(){
	return $this->belongsToMany('App\Property');
    }
    public function preferences(){
	return $this->belongsToMany('App\UserPreference');
    }
    public function wanteds(){
	return $this->belongsToMany('App\Wanted');
    }
}
