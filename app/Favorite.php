<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
	public function user(){
		return $this->belongsTo('\App\User');
	}
	public function property(){
		return $this->belongsTo('\App\Property');
	}
    //
}
