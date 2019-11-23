<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    public function user(){
	return $this->belongsTo('\App\User');
    }
    public function amenities(){
	return $this->belongsToMany('\App\Amenity');
    }
    public function acceptings(){
	return $this->belongsToMany('\App\Accepting');
    }
}
