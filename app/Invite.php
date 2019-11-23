<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
	public function from(){
		return $this->belongsTo('\App\User','user_id_from');
	}
	public function to(){
		return $this->belongsTo('\App\User','user_id_to');
	}
	public function property(){
		return $this->belongsTo('\App\Property');
	}
}
